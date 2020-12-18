<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ZipcodeRepository")
 * @UniqueEntity("id", message="Provided ID already exists")
 */
class Zipcode implements EntityInterface, JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=5, options={"fixed" = true}, unique=true, nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "The id must have exactly 5 characters",
     *      maxMessage = "The id must have exactly 5 characters"
     * )
     * @Assert\NotBlank()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "The city must have at least 5 characters",
     *      maxMessage = "The city must have less than 256 characters"
     * )
     * @Assert\NotBlank()
     */
    private $city;

    public function __construct(string $id = null, string $city = null)
    {
        $this->id = $id;
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'city' => $this->city,
        ];
    }
}
