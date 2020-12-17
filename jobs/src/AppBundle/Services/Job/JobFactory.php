<?php

namespace AppBundle\Services\Job;

use AppBundle\Entity\Job;
use DateTime;

class JobFactory
{
    public function create(array $parameters): Job
    {
        $attributes = [];
        $attributes['serviceId'] = $parameters['serviceId'] ?? null;
        $attributes['zipcodeId'] = $parameters['zipcodeId'] ?? null;
        $attributes['title'] = $parameters['title'] ?? null;
        $attributes['description'] = $parameters['description'] ?? null;
        $attributes['dateToBeDone'] = isset($parameters['dateToBeDone'])
            ? new DateTime($parameters['dateToBeDone'])
            : null;
        $attributes['id'] = $parameters['id'] ?? null;

        return new Job(
            $attributes['serviceId'],
            $attributes['zipcodeId'],
            $attributes['title'],
            $attributes['description'],
            $attributes['dateToBeDone'],
            $attributes['id']
        );
    }
}
