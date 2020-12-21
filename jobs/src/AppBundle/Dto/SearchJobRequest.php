<?php

declare(strict_types=1);

namespace AppBundle\Dto;

use AppBundle\Entity\Job;
use AppBundle\Repository\SearchParametersInterface;

// TODO[petr]: Json serialize properties
class SearchJobRequest implements SearchParametersInterface
{
    private const DEFAULT_DAYS_COUNT = 30;
    private const DEFAULT_LIMIT = 100;
    private const DEFAULT_OFFSET = 0;

    private $daysCount = self::DEFAULT_DAYS_COUNT;

    private $categoryId;

    private $zipcodeId;

    private $limit = self::DEFAULT_LIMIT;

    private $offset = self::DEFAULT_OFFSET;

    public function __construct(
        int $daysCount = self::DEFAULT_DAYS_COUNT,
        ?int $categoryId = null,
        ?int $zipcodeId = null,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ) {
        $this->daysCount = $daysCount;
        $this->categoryId = $categoryId;
        $this->zipcodeId = $zipcodeId;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getDaysCount(): int
    {
        return $this->daysCount;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getZipcodeId(): ?int
    {
        return $this->zipcodeId;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getEntityClassName(): string
    {
        return Job::class;
    }
}
