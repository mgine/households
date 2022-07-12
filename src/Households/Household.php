<?php

namespace App\Households;

use App\Model\Account;
use App\Model\Holding;
use App\Model\Owner;
use App\Model\Security;

//TODO List object
class Household
{
    /**
     * @var array|Account[]
     */
    protected array $accounts;

    /**
     * @var array|Owner[]
     */
    protected array $owners;

    /**
     * @var array|Holding[]
     */
    protected array $holdings;

    /**
     * @var array|Security[]
     */
    protected array $securities;

    public function addAccount(Account $account) : void
    {
        $this->accounts[$account->getId()] = $account;
        $this->reset();
    }

    /**
     * @return array|Account[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    /**
     * @var array|Owner[]
     */
    public function getOwners(): array
    {
        if(isset($this->owners)){
            return $this->owners;
        }

        foreach($this->accounts as $account){
            foreach ($account->getOwners() as $owner){
                $this->owners[$owner->getId()] = $owner;
            }
        }

        return $this->owners;
    }

    /**
     * @var array|Holding[]
     */
    public function getHoldings(): array
    {
        //TODO implementation similar to getOwners
        return [];
    }

    /**
     * @var array|Security[]
     */
    public function getSecurities(): array
    {
        //TODO implementation similar to getOwners
        return [];
    }

    protected function reset(): void
    {
        unset($this->owners);
        unset($this->holdings);
        unset($this->securities);
    }
}