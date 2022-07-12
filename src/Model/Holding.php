<?php

namespace App\Model;

// TODO list object
class Holding
{
    public function __construct(
        protected Security $security,
        protected int $shares
    ){
        if($shares <= 0){
            throw new ModelFactoryValidationException('Holding shares should be positive number');
        }
    }

    public function getId() : string
    {
        return $this->security->getId();
    }

    public function getSecurity() : Security
    {
        return $this->security;
    }

    public function getShares() : int
    {
        return $this->shares;
    }
}