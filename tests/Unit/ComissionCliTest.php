<?php

namespace Tests\Unit;

use \PHPUnit\Framework\TestCase;
use ComissionCli\ComissionCli;

class ComissionCliTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->inputFile = 'test.txt';
    }
    public function testComissionCli(): void
    {
        $renderedResult = "1\n0.46\n1.65\n";
        $comissionCli = $this->createMock(ComissionCli::class);
        $comissionCli->expects(self::once())
                     ->method('getResult')
                     ->with($this->inputFile)
                     ->willReturn($renderedResult);
        $result = $comissionCli->getResult($this->inputFile);
        self::assertEquals($renderedResult, $result);
    }
}
