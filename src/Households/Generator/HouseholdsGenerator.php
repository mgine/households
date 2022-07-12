<?php

namespace App\Households\Generator;

use App\Model\Model;
use App\Households\Household;

class HouseholdsGenerator
{
    public function __construct(
        public GeneratorAlgorithmInterface $algorithm
    ){}

    /**
     * @param Model $bank
     * @return array|Household[]
     */
    public function process(Model $bank): array
    {
        return $this->algorithm->generateHouseholds($bank);
    }
}