<?php

namespace App\Households\Generator;

use App\Households\Generator\GeneratorAlgorithmInterface;
use App\Households\Household;
use App\Model\Account;
use App\Model\Model;

//TODO find out and test if there is maybe some more efficient algorithm for this job

class GroupByOwnersAlgorithm implements GeneratorAlgorithmInterface
{
    public function generateHouseholds(Model $bank): array
    {
        $parsedAccounts = [];
        $households = [];

        foreach ($bank->getAccounts() as $account){
            $household = $this->findHousehold($account, new Household(), $parsedAccounts);
            if($household){
                $households[] = $household;
            }
        }

        return $households;
    }

    protected function findHousehold(Account $account, Household $household, array &$parsedAccounts) : ?Household
    {
        if(isset($parsedAccounts[$account->getId()])){
            return null;
        }

        $parsedAccounts[$account->getId()] = 1;
        $household->addAccount($account);

        foreach ($account->getOwners() as $owner){
            foreach ($owner->getAccounts() as $account){
                $this->findHousehold($account, $household, $parsedAccounts);
            }
        }

        return $household;
    }
}