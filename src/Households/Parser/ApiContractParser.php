<?php

namespace App\Households\Parser;

use App\Households\Household;

class ApiContractParser implements ParserInterface
{
    /**
     * @param array|Household[] $households
     * @return array
     */
    public function parse(array $households): array
    {
        $ret = [];

        foreach ($households as $household){

            if(count($household->getAccounts())){
                $ret[] = [
                    'accounts' => array_keys($household->getAccounts()),
                    'owners' => array_keys($household->getOwners()),
                    ];
            }
        }

        return $ret;
    }
}