<?php

declare(strict_types=1);

namespace AppBundle\Services\Zipcode;

use AppBundle\Entity\EntityInterface;
use AppBundle\Entity\Zipcode as ZipcodeEntity;

class ZipcodeFactory
{
    public function create(array $parameters): EntityInterface
    {
        $attributes = [];
        $attributes['id'] = $parameters['id'] ?? null;
        $attributes['city'] = $parameters['city'] ?? null;

        return new ZipcodeEntity($attributes['id'], $attributes['city']);
    }
}
