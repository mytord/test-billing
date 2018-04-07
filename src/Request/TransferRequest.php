<?php

namespace App\Request;

use App\Request\Mixin\AmountAwareRequest;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TransferRequest.
 */
class TransferRequest
{
    use AmountAwareRequest;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    protected $sourceWalletId;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    protected $recipientWalletId;

    /**
     * TransferRequest constructor.
     * @param int|null $sourceWalletId
     * @param int|null $recipientWalletId
     * @param int|null $amount
     */
    public function __construct(int $sourceWalletId = null, int $recipientWalletId = null, int $amount = null)
    {
        $this->sourceWalletId = $sourceWalletId;
        $this->recipientWalletId = $recipientWalletId;
        $this->amount = $amount;
    }


    /**
     * @return int|null
     */
    public function getSourceWalletId(): ?int
    {
        return $this->sourceWalletId;
    }

    /**
     * @param int|null $sourceWalletId
     */
    public function setSourceWalletId(?int $sourceWalletId)
    {
        $this->sourceWalletId = $sourceWalletId;
    }

    /**
     * @return int|null
     */
    public function getRecipientWalletId(): ?int
    {
        return $this->recipientWalletId;
    }

    /**
     * @param int|null $recipientWalletId
     */
    public function setRecipientWalletId(?int $recipientWalletId)
    {
        $this->recipientWalletId = $recipientWalletId;
    }
}