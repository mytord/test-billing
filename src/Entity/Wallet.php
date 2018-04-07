<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Wallet.
 *
 * @ORM\Entity()
 */
class Wallet
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Balance in cents.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $balance;

    /**
     * @var ArrayCollection|Event[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="wallet", cascade={"persist"})
     */
    private $events;

    /**
     * Wallet constructor.
     * @param int $balance
     */
    public function __construct(int $balance = 0)
    {
        $this->events = new ArrayCollection();
        $this->balance = $balance;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events->toArray();
    }

    /**
     * @param int $amount
     */
    public function withdraw(int $amount): void
    {
        $this->balance -= $amount;
        $this->events->add(
            Event::withdrawal($this, $amount)
        );
    }

    /**
     * @param int $amount
     */
    public function deposit(int $amount): void
    {
        $this->balance += $amount;
        $this->events->add(
            Event::deposit($this, $amount)
        );
    }
}