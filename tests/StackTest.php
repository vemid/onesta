<?php


namespace Tests\Vemid\ProjectOne;

use PHPUnit\Framework\TestCase;

/**
 * Class StackTest
 * @package Tests\Arbor\BillingService
 */
class StackTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertArrayHasKey('foo', ['foo' => 'baz']);
    }
}
