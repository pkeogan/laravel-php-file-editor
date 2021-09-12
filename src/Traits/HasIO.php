<?php

namespace Pkeogan\Traits;

use Pkeogan\Support\Exceptions\FileParseError;
use Pkeogan\Support\PSR2PrettyPrinter;
use PHPParser\Error as PHPParserError;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use Illuminate\Support\Str;

trait HasIO
{


    public function inputDriver($driver = null)
    {
        if (! $driver) {
            return $this->input;
        }

        $this->input = $driver;

        return $this;
    }

    public function outputDriver($driver = null)
    {
        if (! $driver) {
            return $this->output;
        }

        $this->output = $driver;

        return $this;
    }

    public function find($path)
    {
        return $this->load($path);
    }

    public function load($path)
    {
        $content = $this->input->load($path);

        $this->output->setDefaultsFrom($this->input);

        $this->contents($content);

        $this->ast($this->parse());

        $this->originalAst = $this->ast();

        $this->initialModificationHash = $this->getModificationHash();

        return $this;
    }

    public function fromString($code)
    {
        $this->contents($code);

        $this->ast($this->parse());

        $this->originalAst = $this->ast();

        $this->initialModificationHash = $this->getModificationHash();

        return $this;
    }

    public function save($outputPath = false)
    {
       
        $r = $this->render();
        if(!strpos($r, "\n\nclass ") && strpos($r, "\nclass ")){
            $r = str_replace("\nclass ", "\n\nclass ", $r);
        }


        if($this->replacement){
                $r = $this->renderTraits($r);
        }   
     

        $this->output->save($outputPath, $r);

        return $this;
    }

    public function renderTraits($r)
    {
        if($this->traitsCurrentlyUsed){
            $startPos = strpos($r, 'use ' . reset($this->traitsCurrentlyUsed));

            $lastUseTrait = end($this->traitsCurrentlyUsed) . ';';
            $endPos = strpos($r, $lastUseTrait, $startPos) + strlen($lastUseTrait);
            $tagLength = $endPos - $startPos + 1;
    
            return substr_replace( $r, $this->replacement, $startPos, $tagLength );
    
        } else {
            return Str::replaceFirst( "\n{", "\n{\n     ".$this->replacement, $r );
        }   
      
    }

    public function debug()
    {
        $this->output->storage->roots['output'] = $this->output->storage->roots['debug'];
        $this->output->root = config('Pkeogan.roots')['debug'];

        return $this->save();
    }

    public function preview()
    {
        echo $this->render();
    }

    public function parse()
    {
        $this->lexer = new \PhpParser\Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);

        $parser = new \PhpParser\Parser\Php7($this->lexer);

        //$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CloningVisitor());
        
        try {
            $this->originalAst = $parser->parse($this->contents());
            $this->tokens = $this->lexer->getTokens();
        } catch (PHPParserError $error) {
            // rethrow with extra information
            throw new FileParseError($this->input->absolutePath(), $error);
        }

        $ast = $traverser->traverse($this->originalAst);
        
        return $ast;
    }


    public function render()
    {
        //return (new PSR2PrettyPrinter)->prettyPrintFile($this->ast());

        // This wrecks AST (slightly)
        return (new PSR2PrettyPrinter)->printFormatPreserving(
            $this->ast(),
            $this->originalAst,
            $this->tokens
        );
    }

    public function hasModifications()
    {
        return ($this->ast() == null) || $this->getModificationHash() != $this->initialModificationHash;
    }

    protected function getModificationHash()
    {
        return md5(json_encode($this->ast()));
    }
    
    public function dd($method = false)
    {
        dd(
            $method ? $this->$method() : $this
        );
    }

    public function contents($contents = false)
    {
        return $contents ? $this->contents = $contents : $this->contents;
    }

    public function ast($ast = null)
    {
        if ($ast === null) {
            return $this->ast;
        }

        $this->ast = $ast;

        return $this;
    }
}
