<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Dto;
use AppBundle\Entity\Zipcode;
use AppBundle\Repository\ZipcodeRepository;
use AppBundle\Services\EntityFactory\AbstractEntityFactory;
use AppBundle\Services\EntityUpdater\AbstractEntityUpdater;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ZipcodeController extends AbstractController
{
    private $zipcodeRepository;

    public function __construct(
        ZipcodeRepository $zipcodeRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AbstractEntityFactory $entityFactory,
        AbstractEntityUpdater $entityUpdater
    ) {
        parent::__construct($entityManager, $serializer, $validator, $entityFactory, $entityUpdater);
        $this->zipcodeRepository = $zipcodeRepository;
    }

    /**
     * @Rest\Get("/zipcode")
     * @return Response
     */
    public function getAllAction(): Response
    {
        return new JsonResponse($this->zipcodeRepository->findAll(), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/zipcode/{id}")
     * @param Zipcode $zipcode
     * @return Response
     * @ParamConverter(name="id", class="AppBundle\Entity\Zipcode")
     */
    public function getAction(Zipcode $zipcode): Response
    {
        return new JsonResponse($zipcode, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/zipcode")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request): Response
    {
        return $this->validateAndUpsert($request->getContent(), Dto\UpdateZipcodeRequest::class);
    }
}
