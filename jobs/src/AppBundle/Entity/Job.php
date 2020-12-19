<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use JsonSerializable;
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
 */
class Job implements EntityInterface, JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private $id;

    // TODO[petr]: return entity
    // TODO[petr]: rename property
    /**
     * @ORM\Column(type="integer", name="category_id")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\JobCategory")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     * @Assert\NotBlank(message="Job category should not be blank")
     * @AppBundle\Services\Validator\EntityExistsConstraint(
     *     name="Job category",
     *     entityClassName="AppBundle\Entity\JobCategory"
     * )
     * @JMS\Type("integer")
     * @JMS\SerializedName("serviceId")
     */
    private $service_id;

    /**
     * @ORM\Column(type="string", length=5, options={"fixed" = true})
     * @ORM\ManyToOne(targetEntity="App\Entity\Zipdcode")
     * @ORM\JoinColumn(nullable=false, name="category_id", referencedColumnName="id")
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "The zipcode_id must have exactly 5 characters",
     *      maxMessage = "The zipcode_id must have exactly 5 characters"
     * )
     * @Assert\NotBlank(message="Zipcode should not be blank")
     * @AppBundle\Services\Validator\EntityExistsConstraint(
     *     name="Zipcode",
     *     entityClassName="App\Entity\Zipdcode"
     * )
     * @JMS\Type("integer")
     * @JMS\SerializedName("zipcodeId")
     */
    private $zipcode_id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "The title must more than 4 characters",
     *      maxMessage = "The title must have less than 51 characters"
     * )
     * @Assert\NotBlank(message="Title should not be blank")
     * @JMS\Type("string")
     * @JMS\SerializedName("title")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     * @JMS\Type("DateTime<'Y-m-d'>")
     * @JMS\SerializedName("dateToBeDone")
     */
    private $date_to_be_done;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    // TODO[petr]: refactor nullables
    public function __construct(
        int $serviceId = null,
        string $zipcodeId = null,
        string $title = null,
        string $description = null,
        DateTimeInterface $dateToBeDone = null,
        string $id = null
    ) {
        $this->service_id = $serviceId;
        $this->zipcode_id = $zipcodeId;
        $this->title = $title;
        $this->description = $description;
        $this->date_to_be_done = $dateToBeDone;
        $this->created_at = new DateTime();
        $this->id = $id ?? $this->id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getServiceId(): ?int
    {
        return $this->service_id;
    }

    /**
     * @return null|string
     */
    public function getZipcodeId(): ?string
    {
        return $this->zipcode_id;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateToBeDone(): ?DateTimeInterface
    {
        return $this->date_to_be_done;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @PrePersist
     */
    public function resetCreatedAt(): void
    {
        $this->created_at = $this->created_at ?? new DateTime();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'service_id' => $this->service_id,
            'zipcode_id' => $this->zipcode_id,
            'title' => $this->title,
            'description' => $this->description,
            'date_to_be_done' => $this->date_to_be_done,
            'created_at' => $this->created_at,
            'id' => $this->id,
        ];
    }
}
