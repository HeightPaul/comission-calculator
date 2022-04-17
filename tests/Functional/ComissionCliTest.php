<?php

namespace Tests\Functional;

use \PHPUnit\Framework\TestCase;
use ComissionCli\ComissionCli;

class ComissionCliTest extends TestCase
{
    public function testComissionCli(): void
    {
        $comissionCli = new ComissionCli('input.txt');
        $result = $comissionCli->getResult();
        self::assertEquals("1\n0.78\nmissing\nmissing\nmissing\nmissing\n", $result);
    }
}
