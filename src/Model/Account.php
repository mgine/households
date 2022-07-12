<?php

namespace App\Model;

// TODO list object
class Account
{
    public function __construct(
        protected int $id,
        protected array $owners,
        protected array $holdings = []
    ){
        if($id <= 0){
            throw new ModelFactoryValidationException('Holding should be positive');
        }

        if(!count($owners)){
            throw new ModelFactoryValidationException('Account should have owners');
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array|Holding[]
     */
    public function getHoldings(): array
    {
        return $this->holdings;
    }

    /**
     * @return array|Owner[]
     */
    public function getOwners(): array
    {
        return $this->owners;
    }
}