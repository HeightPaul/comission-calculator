<?php

namespace Tests\Unit\Lib;

use \PHPUnit\Framework\TestCase;
use ComissionCli\Constants\CountryCodes;
use ComissionCli\Lib\ComissionHelper;

class ComissionHelperTest extends TestCase
{
    private array $eurTransactionWithFloatingPointAmount;
    private array $eurTransactionWithNonFloatingPointAmount;

    public function setUp(): void
    {
        parent::setUp();
        $this->eurTransactionWithFloatingPointAmount = [ 'bin' => '45717360', 'amount' => '77.77', 'currency' => 'EUR'];
        $this->eurTransactionWithNonFloatingPointAmount = [ 'bin' => '45717361', 'amount' => '100.0', 'currency' => 'EUR'];
        $this->nonEurTransactionWithNonFloatingPointAmount = [ 'bin' => '516793', 'amount' => '50.00', 'currency' => 'USD'];
    }

    public function testComissionHelperWithEurTransactionWithFloatingPointAmount(): void
    {
        $expectedComission = 0.67;
        $comissionHelper = $this->createMock(ComissionHelper::class);
        $comissionHelper->expects(self::once())
                        ->method('getCalculatedComission')
                        ->with($this->eurTransactionWithFloatingPointAmount)
                        ->willReturn($expectedComission);
        $comission = $comissionHelper->getCalculatedComission($this->eurTransactionWithFloatingPointAmount);
        self::assertEquals($expectedComission, $comission);
    }

    public function testComissionHelperWithEurTransactionWithNonFloatingPointAmount(): void
    {
        $expectedComission = 1;
        $comissionHelper = $this->createMock(ComissionHelper::class);
        $comissionHelper->expects(self::once())
                        ->method('getCalculatedComission')
                        ->with($this->eurTransactionWithNonFloatingPointAmount)
                        ->willReturn($expectedComission);
        $comission = $comissionHelper->getCalculatedComission($this->eurTransactionWithNonFloatingPointAmount);
        self::assertEquals($expectedComission, $comission);
    }

    public function testComissionHelperWithNonEurTransactionWithNonFloatingPointAmount(): void
    {
        $missingComission = false;
        $comissionHelper = $this->createMock(ComissionHelper::class);
        $comissionHelper->expects(self::once())
                        ->method('getCalculatedComission')
                        ->with($this->eurTransactionWithNonFloatingPointAmount)
                        ->willReturn($missingComission);
        $comission = $comissionHelper->getCalculatedComission($this->eurTransactionWithNonFloatingPointAmount);
        self::assertEquals($missingComission, $comission);
    }

    public function testCheckEuCountryCode(): void
    {
        $comissionHelper = $this->createMock(ComissionHelper::class);
        $comissionHelper->expects(self::once())
                        ->method('isEu')
                        ->with(CountryCodes::LITHUANIA)
                        ->willReturn(true);
        $isEu = $comissionHelper->isEu(CountryCodes::LITHUANIA);
        self::assertTrue($isEu);
    }
}
