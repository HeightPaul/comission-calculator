<?php

namespace Tests\Unit\Lib;

use \PHPUnit\Framework\TestCase;
use ComissionCli\Lib\ComissionHelper;

class ComissionHelperTest extends TestCase
{
    public function testComissionHelper(): void
    {
        $comissionHelper = new ComissionHelper();
        $transaction = ['bin' => '45717361', 'amount' => '66.66', 'currency' => 'EUR'];
        $comission = $comissionHelper->getCalculatedComission($transaction);
        self::assertEquals('0.67', $comission);
    }
}
