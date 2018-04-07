<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

/**
 * Class WalletTest
 */
class WalletTest extends TestCase
{
    /**
     * @var Wallet
     */
    private $wallet;

    /**
     * @test
     */
    public function withdraw()
    {
        $this->wallet->withdraw(14300);

        $this->assertEquals(-3800, $this->wallet->getBalance());
        $this->assertCount(1, $this->wallet->getEvents());

        $event = $this->wallet->getEvents()[0];

        $this->assertEquals(Event::WITHDRAWAL, $event->getType());
        $this->assertEquals(14300, $event->getAmount());
    }

    /**
     * @test
     */
    public function deposit()
    {
        $this->wallet->deposit(11300);

        $this->assertEquals(21800, $this->wallet->getBalance());
        $this->assertCount(1, $this->wallet->getEvents());

        $event = $this->wallet->getEvents()[0];

        $this->assertEquals(Event::DEPOSIT, $event->getType());
        $this->assertEquals(11300, $event->getAmount());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->wallet = new Wallet(10500);
    }
}
