<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event.
 *
 * @ORM\Entity()
 */
class Event
{
    const WITHDRAWAL = 0;
    const DEPOSIT = 1;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @var Wallet
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Wallet", inversedBy="events")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private $wallet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Event constructor.
     * @param $type
     * @param Wallet $wallet
     * @param int $amount
     */
    public function __construct($type, Wallet $wallet, int $amount)
    {
        $this->type = $type;
        $this->wallet = $wallet;
        $this->amount = $amount;
        $this->createdAt = new \DateTime();
    }

    /**
     * @param Wallet $wallet
     * @param int $amount
     *
     * @return Event
     */
    public static function withdrawal(Wallet $wallet, int $amount): Event
    {
        return new static(self::WITHDRAWAL, $wallet, $amount);
    }

    /**
     * @param Wallet $wallet
     * @param int $amount
     *
     * @return Event
     */
    public static function deposit(Wallet $wallet, int $amount): Event
    {
        return new static(self::DEPOSIT, $wallet, $amount);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return Wallet
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}