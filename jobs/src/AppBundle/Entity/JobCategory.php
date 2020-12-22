<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JobCategoryRepository")
 * @ORM\Table(name="job_category")
 * @UniqueEntity(fields="id", message="Resource with provided ID already exists")
 */
class JobCategory implements EntityInterface, JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id", type="integer", unique=true, nullable=false)
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     * @Assert\NotBlank(message="Job category id is mandatory")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     * @Assert\Length(
     *      min = 5,
     *      max = 255,
     *      minMessage = "Job category name must have at least {{ limit }} characters",
     *      maxMessage = "Job category name must have less than {{ limit }} characters"
     * )
     * @Assert\NotBlank()
     */
    private $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
