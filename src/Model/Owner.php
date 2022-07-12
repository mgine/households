<?php

namespace App\Model;

// TODO list object
class Owner
{
    /**
     * @var array|Account[]
     */
    protected array $accounts = [];

    protected $readOnly = false;

    public function __construct(
        protected string $id
    ){
        if (!preg_match('~^[A-Z]+$~D', $id)) {
            throw new ModelFactoryValidationException("Invalid Owner id '$id' : must use only [A-Z]");
        }

        $strlen = strlen($id);
        if($strlen <= 0 || $strlen > 10){
            throw new ModelFactoryValidationException("Invalid Owner id '$id' : length must be in range 1-10");
        }
    }

    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return array|Account[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    public function registerAccount(Account $account) : void
    {
        if($this->readOnly){
            throw new \Exception('Read only object');
        }

        $this->accounts[$account->getId()] = $account;
    }

    public function markAsReadOnly() : void
    {
        $this->readOnly = true;
    }
}