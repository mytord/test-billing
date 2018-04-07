<?php

namespace App\Request\Mixin;

/**
 * Trait AmountAwareRequest.
 */
trait AmountAwareRequest
{
    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    protected $amount;

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int|null $amount
     */
    public function setAmount(?int $amount)
    {
        $this->amount = $amount;
    }
}