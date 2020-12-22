<?php

declare(strict_types=1);

namespace AppBundle\EventListener;

use AppBundle\Dto\ErrorResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    private const ERROR_MESSAGE_MAP = [
        NotFoundHttpException::class => 'Resource not found.',
    ];

    private const ERROR_MESSAGE_DEFAULT = 'Internal server error.';

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        $errorMessage = self::ERROR_MESSAGE_MAP[get_class($exception)] ?? self::ERROR_MESSAGE_DEFAULT;

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof NotFoundHttpException) {
            $statusCode = $exception->getStatusCode();
        }

        $this->logger->error('Exception occured.', ['exception' => $exception]);

        $event->setResponse(new ErrorResponse([$errorMessage], $statusCode));
    }
}
