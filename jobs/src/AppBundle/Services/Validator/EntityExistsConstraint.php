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
    public $message = '{{ name }} "{{ value }}" was not found';

    /**
     * @var string
     */
    public $name = 'Entity';

    /**
     * @var string
     */
    public $entityClassName;

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
