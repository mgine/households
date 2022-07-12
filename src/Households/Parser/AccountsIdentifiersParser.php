<?php

namespace App\Households\Parser;

use App\Households\Household;

class AccountsIdentifiersParser implements ParserInterface
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
                $ret[] = array_keys($household->getAccounts());
            }
        }

        return $ret;
    }
}