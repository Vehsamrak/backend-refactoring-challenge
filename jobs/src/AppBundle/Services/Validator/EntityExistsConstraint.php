<?php

declare(strict_types=1);

namespace AppBundle\Services\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Validate that entity with given id esists in database
 * @Annotation
 */
class EntityExistsConstraint extends Constraint
{
    /**
     * @var string
     */
    public $messageFound = '{{ name }} #{{ value }} already exists.';

    /**
     * @var string
     */
    public $messageNotFound = '{{ name }} #{{ value }} was not found.';

    /**
     * @var string
     */
    public $name = 'Entity';

    /**
     * Check that entity exists if "true", and not exists if "false"
     * @var bool
     */
    public $exists = true;

    /**
     * @var string
     */
    public $entityClassName;

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
