<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JobRepository")
 * @ORM\Table(
 *     name="job",
 *     indexes={
 *         @ORM\Index(name="fk__job__category_id", columns={"category_id"}),
 *         @ORM\Index(name="fk__job__zipcode_id", columns={"zipcode_id"})
 *     }
 * )
 */
class Job implements EntityInterface, \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid", nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="AppBundle\Services\UuidGenerator\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\JobCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Zipcode")
     * @ORM\JoinColumn(name="zipcode_id", referencedColumnName="id", nullable=false)
     */
    private $zipcode;

    /**
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="date_to_be_done", type="date", nullable=false)
     */
    private $dateToBeDone;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    public function __construct(
        JobCategory $category,
        Zipcode $zipcode,
        string $title,
        \DateTimeInterface $dateToBeDone,
        ?string $description = null
    ) {
        $this->category = $category;
        $this->zipcode = $zipcode;
        $this->title = $title;
        $this->dateToBeDone = $dateToBeDone;
        $this->description = $description;
        $this->createdAt = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCategory(): JobCategory
    {
        return $this->category;
    }

    public function getZipcode(): Zipcode
    {
        return $this->zipcode;
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
        return $this->formatToImmutable($this->dateToBeDone);
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->formatToImmutable($this->createdAt);
    }

    public function setCategory(JobCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function setZipcode(Zipcode $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setDateToBeDone(?\DateTimeInterface $dateToBeDone): self
    {
        $this->dateToBeDone = $dateToBeDone;

        return $this;
    }

    protected function formatToImmutable(\DateTimeInterface $dateTime): \DateTimeImmutable
    {
        return new \DateTimeImmutable($dateTime->format(DATE_ATOM));
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->category->getId(),
            'zipcodeId' => $this->zipcode->getId(),
            'title' => $this->title,
            'description' => $this->description,
            'dateToBeDone' => $this->dateToBeDone->format('Y-m-d'),
            'createdAt' => $this->createdAt->format('Y-m-d'),
        ];
    }
}
