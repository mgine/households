<?php

namespace App\Tests\Households;

use App\Households\Generator\GroupByOwnersAlgorithm;
use App\Households\Parser\AccountsIdentifiersParser;
use App\Model\ModelFactory;
use PHPUnit\Framework\TestCase;

class GroupByOwnersAlgorithmTest extends TestCase
{
    /**
     * @dataProvider dataGenerateHouseholds
     */
    public function testGenerateHouseholds($data, $result){

        $bankFactory = new ModelFactory();
        $bank = $bankFactory->createFromArray($data);

        $algorithm = new GroupByOwnersAlgorithm();
        $households = $algorithm->generateHouseholds($bank);

        $parser = new AccountsIdentifiersParser();
        $accountsIdentifiers = $parser->parse($households);

        $this->assertEquals($result, $accountsIdentifiers);
    }

    public function dataGenerateHouseholds(){
        return [
            'return nothing for empty data' => [
                [ ],
                [ ]
            ],
            'set single account to one household' => [
                [
                    [ 'id' => '1', 'owner' => ['A'] ],
                ],
                [
                    [1],
                ]
            ],
            'set two accounts with same owner to one household' => [
                [
                    [ 'id' => '1', 'owner' => ['A'] ],
                    [ 'id' => '2', 'owner' => ['A'] ],
                ],
                [
                    [1,2],
                ]
            ],
            'set two accounts with different owners to two households' => [
                [
                    [ 'id' => '1', 'owner' => ['A'] ],
                    [ 'id' => '2', 'owner' => ['B'] ],
                ],
                [
                    [1], [2],
                ]
            ],
            'set shared by two owners (A,B) account and account owned by A into one households' => [
                [
                    [ 'id' => '1', 'owner' => ['A'] ],
                    [ 'id' => '2', 'owner' => ['A','B'] ],
                ],
                [
                    [1,2],
                ]
            ],
            'set shared by few owners accounts into one household if accounts have at least one common owner' => [
                [
                    [ 'id' => '1', 'owner' => ['A'] ],
                    [ 'id' => '2', 'owner' => ['A','B'] ],
                    [ 'id' => '3', 'owner' => ['A','C'] ],
                ],
                [
                    [1,2,3],
                ]
            ],
            'set shared by few owners accounts into one household based on common owners chain' => [
                [
                    [ 'id' => '2', 'owner' => ['B','C'] ],
                    [ 'id' => '3', 'owner' => ['C','D'] ],
                    [ 'id' => '4', 'owner' => ['D','E'] ],
                    [ 'id' => '5', 'owner' => ['E','F'] ],
                ],
                [
                    [2,3,4,5],
                ]
            ],
            'set shared by few owners accounts into one household based on common owners chain 2' => [
                [
                    [ 'id' => '2', 'owner' => ['B','C','D'] ],
                    [ 'id' => '3', 'owner' => ['C','E'] ],
                    [ 'id' => '4', 'owner' => ['D','F'] ],
                    [ 'id' => '5', 'owner' => ['E','G'] ],
                ],
                [
                    [2,3,5,4],
                ]
            ],
            'manage two different common owners chain' => [
                [
                    [ 'id' => '2', 'owner' => ['B','C','D'] ],
                    [ 'id' => '8', 'owner' => ['J','K'] ],
                    [ 'id' => '4', 'owner' => ['D','F'] ],
                    [ 'id' => '7', 'owner' => ['I','J'] ],
                    [ 'id' => '5', 'owner' => ['E','G'] ],
                    [ 'id' => '3', 'owner' => ['C','E'] ],
                    [ 'id' => '6', 'owner' => ['H','I'] ],
                ],
                [
                    [2,3,5,4],
                    [8,7,6],
                ]
            ]
        ];
    }
}