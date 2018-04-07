<?php

namespace App\Transaction;

use App\Entity\Wallet;
use App\Exception\TransactionException;
use App\Request\DepositRequest;
use App\Request\TransferRequest;
use App\Request\WithdrawalRequest;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Operator.
 */
class Operator
{
    /**
     * @var EntityManagerInterface
     */
    private $doctrineManager;

    /**
     * TransactionManager constructor.
     * @param EntityManagerInterface $doctrineManager
     */
    public function __construct(EntityManagerInterface $doctrineManager)
    {
        $this->doctrineManager = $doctrineManager;
    }

    /**
     * @param WithdrawalRequest $request
     */
    public function makeWithdrawal(WithdrawalRequest $request): void
    {
        $this->doctrineManager->transactional(function () use ($request) {
            $wallet = $this->loadWallet($request->getWalletId());

            $amount = $request->getAmount();

            $this->assertThereIsMoneyInTheWallet($wallet, $amount);

            $wallet->withdraw($amount);
        });
    }

    /**
     * @param DepositRequest $request
     */
    public function makeDeposit(DepositRequest $request): void
    {
        $this->doctrineManager->transactional(function () use ($request) {
            $wallet = $this->loadWallet($request->getWalletId());
            $wallet->deposit($request->getAmount());
        });
    }

    /**
     * @param TransferRequest $request
     */
    public function makeTransfer(TransferRequest $request): void
    {
        $this->doctrineManager->transactional(function () use ($request) {
            $sourceWallet = $this->loadWallet($request->getSourceWalletId());
            $recipientWallet = $this->loadWallet($request->getRecipientWalletId());
            $amount = $request->getAmount();

            $this->assertThereIsMoneyInTheWallet($sourceWallet, $amount);

            $sourceWallet->withdraw($amount);
            $recipientWallet->deposit($amount);
        });
    }

    /**
     * @param int $id
     *
     * @return Wallet
     */
    private function loadWallet(int $id): Wallet
    {
        // The nail: row lock on DB level (FOR UPDATE notation)
        $wallet = $this->doctrineManager->find(Wallet::class, $id, LockMode::PESSIMISTIC_WRITE);

        if (!$wallet) {
            throw TransactionException::walletNotFound($id);
        }

        return $wallet;
    }

    /**
     * @param Wallet $wallet
     * @param int $amount
     */
    private function assertThereIsMoneyInTheWallet(Wallet $wallet, int $amount)
    {
        if ($amount > $wallet->getBalance()) {
            throw TransactionException::notEnoughMoney();
        }
    }
}