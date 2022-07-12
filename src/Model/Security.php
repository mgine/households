<?php

namespace App\Model;

class Security
{
    public function __construct(
        protected string $id
    ){
        if (!preg_match('~^[A-Z]+$~D', $id)) {
            throw new ModelFactoryValidationException("Invalid Security id '$id' : must use only [A-Z]");
        }

        $strlen = strlen($id);
        if($strlen != 4){
            throw new ModelFactoryValidationException("Invalid Security id '$id' : length must be 4");
        }
    }

    public function getId(): string
    {
        return $this->id;
    }
}