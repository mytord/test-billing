<?php

namespace App\Request;

use App\Request\Mixin\AmountAwareRequest;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DepositRequest.
 */
class DepositRequest
{
    use AmountAwareRequest;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    protected $walletId;

    /**
     * DepositRequest constructor.
     * @param int|null $walletId
     * @param int|null $amount
     */
    public function __construct(int $walletId = null, int $amount = null)
    {
        $this->walletId = $walletId;
        $this->amount = $amount;
    }

    /**
     * @return int|null
     */
    public function getWalletId(): ?int
    {
        return $this->walletId;
    }

    /**
     * @param int|null $walletId
     */
    public function setWalletId(?int $walletId)
    {
        $this->walletId = $walletId;
    }
}