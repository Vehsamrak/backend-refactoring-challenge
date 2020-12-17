<?php

declare(strict_types=1);

namespace AppBundle\Services\JobCategory;

use AppBundle\Entity\Service as EntityService;
use AppBundle\Entity\Service;

class JobCategoryFactory
{
    public function create(array $parameters): Service
    {
        $attributes = [];
        $attributes['id'] = $parameters['id'] ?? null;
        $attributes['name'] = $parameters['name'] ?? null;

        return new EntityService($attributes['id'], $attributes['name']);
    }
}
