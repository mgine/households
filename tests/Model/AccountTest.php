<?php

namespace App\Tests\Model;

use App\Model\Account;
use App\Model\Owner;
use App\Model\ModelFactoryValidationException;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testGetters(){

        $id = 2;
        $owners = [
            $this->createMock(Owner::class)
        ];
        $account = new Account($id, $owners);

        $this->assertEquals($id, $account->getId());
        $this->assertEquals($owners, $account->getOwners());
    }

    public function testIdentifierValidation(){

        $id = -1;
        $owners = [
            $this->createMock(Owner::class)
        ];
        $this->expectException(ModelFactoryValidationException::class);
        new Account($id, $owners);
    }

    public function testOwnerValidation(){

        $id = 2;
        $owners = [];
        $this->expectException(ModelFactoryValidationException::class);
        new Account($id, $owners);
    }
}