<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\EntityInterface;

interface SearchRepositoryInterface
{
    /**
     * @param SearchParametersInterface $searchParameters
     * @return EntityInterface[]
     */
    public function findAllByParameters(SearchParametersInterface $searchParameters): array;
}
