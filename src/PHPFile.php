<?php

namespace Pkeogan;

use Pkeogan\Drivers\InputInterface;
use Pkeogan\Drivers\OutputInterface;
use Pkeogan\Traits\DelegatesAPICalls;
use Pkeogan\Traits\HasDirectiveDefaults;
use Pkeogan\Traits\HasDirectiveHandlers;
use Pkeogan\Traits\HasIO;

class PHPFile
{
    use HasIO;
    use DelegatesAPICalls;
    use HasDirectiveDefaults;
    use HasDirectiveHandlers;

    protected $input;

    protected $output;

    public $contents;

    public $replacement = false;
    public $traitsCurrentlyUsed = false;

    protected $fileQueryBuilder = Endpoints\PHP\PHPFileQueryBuilder::class;

    protected $ast;

    protected $initialModificationHash;

    protected $originalAst;

    protected $tokens;

    protected $lexer;

    protected $directives = [];

    protected const endpointProviders = [
        // Utilities
        Endpoints\SyntacticSweetener::class,
        Endpoints\PHP\AstQuery::class,
        Endpoints\PHP\ReflectionProxy::class,

        // Resources
        Endpoints\PHP\Maker::class,
        Endpoints\PHP\Property::class,
        Endpoints\PHP\ClassConstant::class,
        Endpoints\PHP\MethodNames::class,
        Endpoints\PHP\Namespace_::class,
        Endpoints\PHP\Use_::class,
        Endpoints\PHP\ClassName::class,
        Endpoints\PHP\Extends_::class,
        Endpoints\PHP\Implements_::class,
        Endpoints\PHP\TraitUse::class,
    ];

    public function __construct(
        string $input = \Pkeogan\Drivers\FileInput::class,
        string $output = \Pkeogan\Drivers\FileOutput::class
    ) {
        $this->input = $input;
        $this->output = $output;
    }

    public function endpointProviders()
    {
        return collect(self::endpointProviders)->push(
            $this->fileQueryBuilder
        );
    }
}
