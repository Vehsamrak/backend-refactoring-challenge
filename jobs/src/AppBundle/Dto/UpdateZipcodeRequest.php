<?php

declare(strict_types=1);

namespace AppBundle\Dto;

use AppBundle\Entity\Zipcode;
use AppBundle\Services\EntityFactory\EntityAwareInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateZipcodeRequest implements EntityAwareInterface
{
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "The id must have exactly 5 characters.",
     *      maxMessage = "The id must have exactly 5 characters."
     * )
     * @Assert\NotBlank(message="The id should not be blank.")
     * @AppBundle\Services\Validator\EntityExistsConstraint(
     *     name="The id",
     *     entityClassName="AppBundle\Entity\Zipcode",
     *     exists=false
     * )
     */
    private $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("city")
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "The city must have at least 3 characters.",
     *      maxMessage = "The city must have less than 51 characters."
     * )
     * @Assert\NotBlank(message="The city should not be blank.")
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

    public function getEntityClassName(): string
    {
        return Zipcode::class;
    }
}
