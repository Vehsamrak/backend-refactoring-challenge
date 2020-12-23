<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ZipcodeRepository")
 * @ORM\Table(name="zipcode")
 */
class Zipcode implements EntityInterface, \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id", type="string", length=5, options={"fixed" = true}, unique=true, nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(name="city", type="string", length=50, nullable=false)
     */
    private $city;

    public function __construct(string $id, string $city)
    {
        $this->id = $id;
        $this->city = $city;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'city' => $this->city,
        ];
    }
}
