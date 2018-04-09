<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\Type\DepositRequestType;
use App\Form\Type\TransferRequestType;
use App\Form\Type\WithdrawalRequestType;
use App\Request\DepositRequest;
use App\Request\TransferRequest;
use App\Request\WithdrawalRequest;
use App\Transaction\Operator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class WalletController.
 */
class WalletController extends Controller
{
    /**
     * @Route("/{id}")
     * @Method("GET")
     *
     * @param Wallet $wallet
     *
     * @return JsonResponse
     */
    public function getWallet(Wallet $wallet): JsonResponse
    {
        return new JsonResponse(
            $this->get('serializer')->normalize($wallet, null, ['groups' => ['wallet-read']])
        );
    }

    /**
     * @Route("/deposit")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deposit(Request $request): JsonResponse
    {
        $operator = $this->get(Operator::class);

        /** @var DepositRequest $depositRequest */
        $depositRequest = $this->getValidDtoFromRequest($request, DepositRequestType::class);

        $operator->makeDeposit($depositRequest);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @Route("/withdrawal")
     * @Method("POST")
     *
     * @param Request $request

     * @return JsonResponse
     */
    public function withdrawal(Request $request): JsonResponse
    {
        $operator = $this->get(Operator::class);

        /** @var WithdrawalRequest $withdrawalRequest */
        $withdrawalRequest = $this->getValidDtoFromRequest($request, WithdrawalRequestType::class);

        $operator->makeWithdrawal($withdrawalRequest);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @Route("/transfer")
     * @Method("POST")
     *
     * @param Request $request

     * @return JsonResponse
     */
    public function transfer(Request $request): JsonResponse
    {
        $operator = $this->get(Operator::class);

        /** @var TransferRequest $transferRequest */
        $transferRequest = $this->getValidDtoFromRequest($request, TransferRequestType::class);

        $operator->makeTransfer($transferRequest);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string $formTypeClass
     *
     * @throws BadRequestHttpException
     *
     * @return mixed
     */
    protected function getValidDtoFromRequest(Request $request, string $formTypeClass)
    {
        $form = $this->createForm($formTypeClass);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            throw new BadRequestHttpException('Invalid request. Please check requested fields and try again.');
        }

        return $form->getData();
    }
}