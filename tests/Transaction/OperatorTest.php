<?php

namespace App\Tests\Transaction;

use App\Entity\Wallet;
use App\Request\DepositRequest;
use App\Request\TransferRequest;
use App\Request\WithdrawalRequest;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use App\Transaction\Operator;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class OperatorTest
 */
class OperatorTest extends TestCase
{
    /**
     * @var Operator
     */
    private $operator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Wallet
     */
    private $firstWallet;

    /**
     * @var Wallet
     */
    private $secondWallet;

    /**
     * @test
     */
    public function makeWithdrawalWillMakeCorrectWithdraw()
    {
        $this->firstWallet
            ->withdraw(350)
            ->shouldBeCalled();

        $this->operator->makeWithdrawal(new WithdrawalRequest(1, 350));
    }

    /**
     * @test
     *
     * @expectedException \App\Exception\TransactionException
     * @expectedExceptionMessage Wallet 1 not found. Maybe you used wrong ID
     */
    public function makeWithdrawalShouldThrowExceptionWhenWalletNotFound()
    {
        $this->entityManager
            ->find(Wallet::class, 1, LockMode::PESSIMISTIC_WRITE)
            ->willReturn(null);

        $this->operator->makeWithdrawal(new WithdrawalRequest(1, 350));
    }

    /**
     * @test
     *
     * @expectedException \App\Exception\TransactionException
     * @expectedExceptionMessage Not enough money in the wallet. Please make a deposit.
     */
    public function makeWithdrawalShouldThrowExceptionWhenNotEnoughMoney()
    {
        $this->firstWallet
            ->getBalance()
            ->willReturn(100);

        $this->operator->makeWithdrawal(new WithdrawalRequest(1, 350));
    }

    /**
     * @test
     */
    public function makeDepositWillMakeCorrectDeposit()
    {
        $this->firstWallet
            ->deposit(500)
            ->shouldBeCalled();

        $this->operator->makeDeposit(new DepositRequest(1, 500));
    }

    /**
     * @test
     *
     * @expectedException \App\Exception\TransactionException
     * @expectedExceptionMessage Wallet 1 not found. Maybe you used wrong ID
     */
    public function makeDepositShouldThrowExceptionWhenWalletNotFound()
    {
        $this->entityManager
            ->find(Wallet::class, 1, LockMode::PESSIMISTIC_WRITE)
            ->willReturn(null);

        $this->operator->makeDeposit(new DepositRequest(1, 200));
    }

    /**
     * @test
     */
    public function makeTransferWillMakeCorrectTransfer()
    {
        $this->firstWallet
            ->withdraw(250)
            ->shouldBeCalled();

        $this->secondWallet
            ->deposit(250)
            ->shouldBeCalled();

        $this->operator->makeTransfer(new TransferRequest(1, 2, 250));
    }

    /**
     * @test
     * @expectedException \App\Exception\TransactionException
     * @expectedExceptionMessage Not enough money in the wallet. Please make a deposit.
     */
    public function makeTransferShouldThrowExceptionWhenNotEnoughMoneyInTheSourceWallet()
    {
        $this->firstWallet
            ->getBalance()
            ->willReturn(100);

        $this->operator->makeTransfer(new TransferRequest(1, 2, 250));
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->entityManager = $this->prophesize(EntityManager::class);
        $this->entityManager
            ->transactional(Argument::any())
            ->will(function ($args) {
                $args[0]->__invoke();
            })
            ->shouldBeCalled();

        $this->firstWallet = $this->createWallet(1, 1000);
        $this->secondWallet = $this->createWallet(2, 250);

        $this->operator = new Operator($this->entityManager->reveal());
    }

    /**
     * @param int $id
     * @param int $balance
     *
     * @return Wallet|ObjectProphecy
     */
    protected function createWallet(int $id, int $balance = 0): ObjectProphecy
    {
        $wallet = $this->prophesize(Wallet::class);
        $wallet->getBalance()->willReturn($balance);

        $this->entityManager
            ->find(Wallet::class, $id, LockMode::PESSIMISTIC_WRITE)
            ->willReturn($wallet);

        return $wallet;
    }
}
