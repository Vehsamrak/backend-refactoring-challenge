<?php

declare(strict_types=1);

namespace AppBundle\Dto;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorResponse extends ErrorResponse
{
    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct($this->formatErrors($errors), Response::HTTP_BAD_REQUEST);
    }

    public function formatErrors(ConstraintViolationListInterface $errors): array
    {
        $errorMessages = [];
        /** @var ConstraintViolationList $errors */
        foreach ($errors as $validationError) {
            $errorMessages[$validationError->getPropertyPath()] = $validationError->getMessage();
        }

        return $errorMessages;
    }
}
