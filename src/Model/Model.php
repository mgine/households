<?php

namespace App\Model;

class Model
{
    public function __construct(
        protected array $accounts,
        protected array $owners,
        protected array $securities
    ){}

    /**
     * @return array|Account[]
     */
    public function getAccounts() : array
    {
        return $this->accounts;
    }

    /**
     * @return array|Owner[]
     */
    public function getOwners() : array
    {
        return $this->owners;
    }

    /**
     * @return array|Security[]
     */
    public function getSecurities() : array
    {
        return $this->securities;
    }
}