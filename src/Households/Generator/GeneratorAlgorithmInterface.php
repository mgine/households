<?php

namespace App\Households\Generator;

use App\Model\Model;

interface GeneratorAlgorithmInterface
{
    public function generateHouseholds(Model $bank): array;
}