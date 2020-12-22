<?php

declare(strict_types=1);

namespace AppBundle\Dto;

use AppBundle\Entity\Job;
use AppBundle\Services\EntityFactory\EntityAwareInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateJobRequest implements EntityAwareInterface
{
    /**
     * @JMS\Type("integer")
     * @JMS\SerializedName("categoryId")
     * @Assert\NotBlank(message="The categoryId should not be blank.")
     * @AppBundle\Services\Validator\EntityExistsConstraint(
     *     name="The category",
     *     entityClassName="AppBundle\Entity\JobCategory",
     *     exists=true
     * )
     * @var int
     */
    private $categoryId;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("zipcodeId")
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "The zipcodeId must have exactly 5 characters.",
     *      maxMessage = "The zipcodeId must have exactly 5 characters."
     * )
     * @Assert\NotBlank(message="The zipcodeId should not be blank.")
     * @AppBundle\Services\Validator\EntityExistsConstraint(
     *     name="The zipcode",
     *     entityClassName="AppBundle\Entity\Zipcode",
     *     exists=true
     * )
     * @var string
     */
    private $zipcodeId;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("title")
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "The title must have more than 4 characters.",
     *      maxMessage = "The title must have less than 51 characters."
     * )
     * @Assert\NotBlank(message="The title should not be blank.")
     * @var string
     */
    private $title;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     * @var string|null
     */
    private $description;

    /**
     * @JMS\Type("DateTime<'Y-m-d'>")
     * @JMS\SerializedName("dateToBeDone")
     * @Assert\Date(message="The {{ value }} is not a valid date.")
     * @var \DateTimeInterface
     */
    private $dateToBeDone;


    public function __construct(
        int $categoryId,
        string $zipcodeId,
        string $title,
        \DateTimeInterface $dateToBeDone,
        ?string $description = null
    ) {
        $this->categoryId = $categoryId;
        $this->zipcodeId = $zipcodeId;
        $this->title = $title;
        $this->dateToBeDone = $dateToBeDone;
        $this->description = $description;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getZipcodeId(): string
    {
        return $this->zipcodeId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDateToBeDone(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->dateToBeDone->format(DATE_ATOM));
    }

    public function getEntityClassName(): string
    {
        return Job::class;
    }
}
