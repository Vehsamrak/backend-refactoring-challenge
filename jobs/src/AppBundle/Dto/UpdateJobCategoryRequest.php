<?php

declare(strict_types=1);

namespace AppBundle\Dto;

use AppBundle\Entity\JobCategory;
use AppBundle\Services\EntityFactory\EntityAwareInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateJobCategoryRequest implements EntityAwareInterface
{
    /**
     * @JMS\Type("integer")
     * @JMS\SerializedName("id")
     * @Assert\NotBlank(message="The id should not be blank.")
     * @AppBundle\Services\Validator\EntityExistsConstraint(
     *     name="The id",
     *     entityClassName="AppBundle\Entity\JobCategory",
     *     exists=false
     * )
     */
    private $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     * @Assert\Length(
     *      min = 5,
     *      max = 255,
     *      minMessage = "The name must have at least 5 characters.",
     *      maxMessage = "The name must have less than 256 characters."
     * )
     * @Assert\NotBlank(message="The name should not be blank.")
     */
    private $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEntityClassName(): string
    {
        return JobCategory::class;
    }
}
