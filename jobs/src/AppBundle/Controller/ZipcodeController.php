<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Zipcode;
use AppBundle\Repository\ZipcodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ZipcodeController extends AbstractController
{
    private $zipcodeRepository;

    public function __construct(
        ZipcodeRepository $zipcodeRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        parent::__construct($entityManager, $serializer, $validator);
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
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function getAction($id): Response
    {
        // TODO[petr]: use integer instead of string
        $entity = $this->zipcodeRepository->findById($id);
        if (!$entity) {
            throw new NotFoundHttpException(
                sprintf('The resource \'%s\' was not found.', $id)
            );
        }

        return new JsonResponse($entity, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/zipcode")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request): Response
    {
        return $this->validateAndCreate($request->getContent(), Zipcode::class);
    }
}
