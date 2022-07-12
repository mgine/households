<?php

namespace App\Households\Parser;

use App\Households\Household;

interface ParserInterface
{
    /**
     * @param array|Household[] $households
     * @return array
     */
    public function parse(array $households): array;
}