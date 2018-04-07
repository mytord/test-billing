<?php

namespace App\Exception;

/**
 * Class TransactionException.
 */
class TransactionException extends \LogicException
{
    /**
     * @return TransactionException
     */
    public static function notEnoughMoney(): TransactionException
    {
        return new static('Not enough money in the wallet. Please make a deposit.');
    }

    /**
     * @param int $walletId
     *
     * @return TransactionException
     */
    public static function walletNotFound(int $walletId): TransactionException
    {
        return new static(sprintf('Wallet %d not found. Maybe you used wrong ID', $walletId));
    }
}