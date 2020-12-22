<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Dto;
use AppBundle\Repository\JobRepository;
use AppBundle\Services\EntityFactory\AbstractEntityFactory;
use AppBundle\Services\EntityUpdater\AbstractEntityUpdater;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JobController extends AbstractController
{
    // TODO[petr]: use param converter instead
    private $jobRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        JobRepository $jobRepository,
        AbstractEntityFactory $entityFactory,
        AbstractEntityUpdater $entityUpdater
    ) {
        parent::__construct($entityManager, $serializer, $validator, $entityFactory, $entityUpdater);
        $this->jobRepository = $jobRepository;
    }

    /**
     * @Rest\Get("/job")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function searchAction(Request $request): Response
    {
        return $this->validateAndSearch($request->query->all(), Dto\SearchJobRequest::class);
    }

    /**
     * @Rest\Get("/job/{id}")
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function getAction($id): Response
    {
        // TODO[petr]: paramconverter
        $job = $this->jobRepository->findById($id);
        if (null === $job) {
            // TODO[petr]: Use ErrorResponse
            throw new NotFoundHttpException(
                sprintf('The resource \'%s\' was not found.', $id)
            );
        }

        return new JsonResponse($job, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/job")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request): Response
    {
        return $this->validateAndUpsert($request->getContent(), Dto\UpdateJobRequest::class);
    }

    /**
     * @Rest\Put("/job/{id}")
     * @param string  $id
     * @param Request $request
     * @return Response
     */
    public function putAction(string $id, Request $request): Response
    {
        // TODO[petr]: use paramconverter
        $job = $this->jobRepository->findById($id);
        if (null === $job) {
            return new JsonResponse('Job not found', Response::HTTP_NOT_FOUND);
        }

        return $this->validateAndUpsert($request->getContent(), Dto\UpdateJobRequest::class, $id);
    }
}
