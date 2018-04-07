<?php

namespace App\EventListener;

use App\Exception\TransactionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionListener.
 */
class ExceptionListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 256]
            ]
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        switch (true) {
            case $exception instanceof TransactionException:
            case $exception instanceof BadRequestHttpException:

                $response = new JsonResponse(['message' => $exception->getMessage(), 'code' => $exception->getCode()], Response::HTTP_BAD_REQUEST);
                $event->setResponse($response);
                $event->stopPropagation();

                break;
        }
    }
}