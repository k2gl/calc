<?php

declare(strict_types=1);

namespace App\EventListener;

use Fusonic\HttpKernelBundle\Exception\ConstraintViolationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use function trim;

#[AsEventListener]
class BadRequestListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        do {
            if ($exception instanceof BadRequestHttpException) {
                $this->handleBadRequestHttpException($event);

                return;
            }

            if ($exception instanceof ConstraintViolationException) {
                $this->handleConstraintViolationException($event);

                return;
            }
        } while (null !== $exception = $exception->getPrevious());
    }

    public function handleBadRequestHttpException(ExceptionEvent $event): void
    {
        $event->setResponse(
            new JsonResponse(
                data:   [
                            'error' => $event->getThrowable()->getMessage(),
                            'type' => 'InvalidJson',
                        ],
                status: Response::HTTP_BAD_REQUEST,
            ),
        );
    }

    public function handleConstraintViolationException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ConstraintViolationException) {
            return;
        }

        $violations = [];

        foreach ($exception->getConstraintViolationList() as $constraintViolation) {
            $violations[] = [
                'code' => $constraintViolation->getCode(),
                'message' => $constraintViolation->getMessage(),
                'propertyPath' => trim($constraintViolation->getPropertyPath(), '$'),
            ];
        }

        $event->setResponse(
            new JsonResponse(
                data:   [
                            'error' => 'The request seems to contain invalid values',
                            'type' => 'UnprocessableContent',
                            'violations' => $violations,
                        ],
                status: Response::HTTP_BAD_REQUEST,
            ),
        );
    }
}
