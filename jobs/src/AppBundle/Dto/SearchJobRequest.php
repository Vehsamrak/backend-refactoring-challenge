<?php

declare(strict_types=1);

namespace AppBundle\Dto;

class SearchJobRequest
{
    private $daysCount;

    private $categoryId;

    private $zipcodeId;

    private $limit;

    private $offset;

    public function __construct(
        int $daysCount,
        ?int $categoryId = null,
        ?int $zipcodeId = null,
        int $limit = 100,
        int $offset = 0
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
}
