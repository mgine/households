<?php

namespace App\Model;

class ModelFactory
{
    protected array $accounts = [];
    protected array $owners = [];
    protected array $securities = [];

    public function createFromArray(array $data): ?Model
    {
        $this->accounts = [];
        $this->owners = [];
        $this->securities = [];

        foreach ($data as $accountData){

            $accountHoldings = [];
            if(isset($accountData['holdings'])){
                if(!is_array($accountData['holdings'])){
                    throw new ModelFactoryValidationException('Holding data should be array but "'.json_encode($accountData['holdings'].'" is given'));
                }
                $accountHoldings = $this->createHoldingsList($accountData['holdings']);
            }

            if(empty($accountData['owner']) || !is_array($accountData['owner'])){
                throw new ModelFactoryValidationException('Wrong owner data');
            }
            $accountOwners = $this->createOwnersList($accountData['owner']);

            $accountId = $accountData['id'] ?? 0;
            $account = new Account((int) $accountData['id'], $accountOwners, $accountHoldings);

            foreach ($accountOwners as $owner){
                $owner->registerAccount($account);
            }

            $this->accounts[$accountId] = $account;
        }

        foreach ($this->owners as $owner){
            $owner->markAsReadOnly();
        }

        return new Model($this->accounts, $this->owners, $this->securities);
    }

    // TODO - move to factory
    protected function createOwnersList(array $ownersData): array
    {
        $accountOwners = [];

        foreach($ownersData as $ownerId){
            $owner = $this->owners[$ownerId] ?? null;
            if(!$owner){
                $owner = new Owner($ownerId);
                $this->owners[$ownerId] = $owner;
            }
            $accountOwners[$owner->getId()] = $owner;
        }

        return $accountOwners;
    }

    // TODO - move to factory
    protected function createHoldingsList(array $holdingData): array
    {
        $accountHoldings = [];

        foreach ($holdingData as $holding){
            $securityId = $holding['security'];
            $security = $this->securities[$securityId] ?? null;

            if(!$security){
                $security = new Security($securityId);
                $this->securities[$securityId] = $security;
            }

            $holding = new Holding($security, (int) $holding['shares']);

            if(isset($accountHoldings[$holding->getId()])){
                /** @var Holding $currentHolding */
                $currentHolding = $accountHoldings[$holding->getId()];
                $newHolding = new Holding($holding->getSecurity(), $currentHolding->getShares() + $holding->getShares());
            }else{
                $newHolding = $holding;
            }

            $accountHoldings[$newHolding->getId()] = $newHolding;
        }

        return $accountHoldings;
    }
}