<?php

namespace Pkeogan\Endpoints\PHP;

use Pkeogan\Endpoints\EndpointProvider;

class TraitUse extends EndpointProvider
{
    /**
     * @example Get class traits
     * @source $file->trait()
     *
     * @example Set class traits
     * @source INCOMPLETE
     *
     * @example Add class traits
     * @source INCOMPLETE
     *
     * @param string $value
     * @return mixed
     */


    public function trait(String $trait)
    {



        $traitsCurrentlyUsed =  $this->get();
        $this->file->traitsCurrentlyUsed  = $traitsCurrentlyUsed;

        $replacement = "";

        $traitsSorted  = collect($traitsCurrentlyUsed);

        if(!$traitsSorted->has($trait))
        {
            $traitsSorted->push($trait);
        }

        $traitsSorted = $traitsSorted->sortBy(function($string) {
            return strlen($string);
        })->unique()->toArray();
        

        foreach($traitsSorted as $key=>$trait)
        {
            if($key === array_key_first($traitsSorted) && $key === array_key_last($traitsSorted) ){
                $replacement = "use " . $trait . ";\n";
            }elseif($key === array_key_first($traitsSorted)) {
                $replacement = "use " . $trait . ",\n";
            }elseif ($key === array_key_last($traitsSorted)) {
                $replacement .= "        ". $trait . ";\n";
            } else { 
                $replacement .= "        ". $trait . ",\n";
            }
        }

        
        $this->file->replacement = $replacement;

        return $this->file->continue();
    }

    protected function get()
    {

        return $this->file->astQuery()
            ->traitUse()
            ->remember('formatted_traits', function ($node) {
                return collect($node->traits)->map(function ($trait) {
                    return join('\\', $trait->parts);
                })->toArray();
            })
            ->recall('formatted_traits')
            ->first();
    }

    protected function add($value)
    {
        return $this->file->continue();
    }

    protected function set($value)
    {
        return $this->file->continue();
    }
}
