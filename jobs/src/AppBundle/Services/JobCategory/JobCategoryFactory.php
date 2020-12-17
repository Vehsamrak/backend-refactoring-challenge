<?php

declare(strict_types=1);

namespace AppBundle\Services\JobCategory;

use AppBundle\Entity\JobCategory;

class JobCategoryFactory
{
    public function create(array $parameters): JobCategory
    {
        $attributes = [];
        $attributes['id'] = $parameters['id'] ?? null;
        $attributes['name'] = $parameters['name'] ?? null;

        return new JobCategory($attributes['id'], $attributes['name']);
    }
}
