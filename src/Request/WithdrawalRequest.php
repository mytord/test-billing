<?php

namespace App\Request;

use App\Request\Mixin\AmountAwareRequest;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class WithdrawalRequest.
 */
class WithdrawalRequest
{
    use AmountAwareRequest;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    protected $walletId;

    /**
     * WithdrawalRequest constructor.
     * @param int $walletId
     * @param int $amount
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