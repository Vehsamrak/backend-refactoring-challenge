<?php

declare(strict_types=1);

namespace AppBundle\Repository;

interface SearchParametersInterface
{
    public function getEntityClassName(): string;

    public function getLimit(): int;

    public function getOffset(): int;
}
