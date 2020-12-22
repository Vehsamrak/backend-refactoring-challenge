<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Datetime;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JobRepository")
 * @ORM\Table(
 *     name="job",
 *     indexes={
 *         @ORM\Index(name="fk__job__category_id", columns={"category_id"}),
 *         @ORM\Index(name="fk__job__zipcode_id", columns={"zipcode_id"})
 *     }
 * )
 * @HasLifecycleCallbacks
 * @UniqueEntity(fields="id", message="Resource with provided ID already exists")
 */
class Job implements EntityInterface, JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid", nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="AppBundle\Services\UuidGenerator\UuidGenerator")
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private $id;

    // TODO[petr]: return entity
    /**
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\JobCategory")
     * @ORM\JoinColumn(name="category_id", nullable=false, referencedColumnName="id")
     * @Assert\NotBlank(message="Job category should not be blank")
     * @AppBundle\Services\Validator\EntityExistsConstraint(
     *     name="Job category",
     *     entityClassName="AppBundle\Entity\JobCategory"
     * )
     * @JMS\Type("integer")
     * @JMS\SerializedName("categoryId")
     */
    private $categoryId;

    // TODO[petr]: return entity
    /**
     * @ORM\Column(name="zipcode_id", type="string", length=5, options={"fixed" = true}, nullable=false)
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Zipcode")
     * @ORM\JoinColumn(name="zipcode_id", referencedColumnName="id", nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "The zipcodeId must have exactly 5 characters",
     *      maxMessage = "The zipcodeId must have exactly 5 characters"
     * )
     * @Assert\NotBlank(message="Zipcode should not be blank")
     * @AppBundle\Services\Validator\EntityExistsConstraint(
     *     name="Zipcode",
     *     entityClassName="AppBundle\Entity\Zipcode"
     * )
     * @JMS\Type("integer")
     * @JMS\SerializedName("zipcodeId")
     */
    private $zipcodeId;

    /**
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "The title must have more than 4 characters",
     *      maxMessage = "The title must have less than 51 characters"
     * )
     * @Assert\NotBlank(message="Title should not be blank")
     * @JMS\Type("string")
     * @JMS\SerializedName("title")
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     */
    private $description;

    /**
     * @ORM\Column(name="date_to_be_done", type="date")
     * @Assert\Date()
     * @JMS\Type("DateTime<'Y-m-d'>")
     * @JMS\SerializedName("dateToBeDone")
     */
    private $dateToBeDone;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    // TODO[petr]: refactor nullables
    public function __construct(
        int $categoryId,
        string $zipcodeId,
        string $title,
        ?string $description = null,
        ?DateTimeInterface $dateToBeDone = null
    ) {
        $this->categoryId = $categoryId;
        $this->zipcodeId = $zipcodeId;
        $this->title = $title;
        $this->description = $description;
        $this->dateToBeDone = $dateToBeDone;
        $this->createdAt = new DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCategory(): int
    {
        return $this->categoryId;
    }

    public function getZipcodeId(): string
    {
        return $this->zipcodeId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    // TODO[petr]: make immutable in getter
    public function getDateToBeDone(): ?DateTimeInterface
    {
        return $this->dateToBeDone;
    }

    // TODO[petr]: make immutable in getter
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @PrePersist
     */
    public function resetCreatedAt(): void
    {
        $this->createdAt = $this->createdAt ?? new DateTime();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->categoryId,
            'zipcodeId' => $this->zipcodeId,
            'title' => $this->title,
            'description' => $this->description,
            'dateToBeDone' => $this->dateToBeDone,
            'createdAt' => $this->createdAt,
        ];
    }
}
