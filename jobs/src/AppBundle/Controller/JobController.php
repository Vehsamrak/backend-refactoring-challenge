<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use AppBundle\Repository\JobRepository;
use AppBundle\Services\Job\Job as JobService;
use AppBundle\Services\Job\JobFactory;
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
    private $jobService;

    private $jobFactory;

    private $jobRepository;

    public function __construct(
        JobService $jobService,
        JobFactory $jobFactory,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        JobRepository $jobRepository
    ) {
        parent::__construct($entityManager, $serializer, $validator);
        $this->jobService = $jobService;
        $this->jobFactory = $jobFactory;
        $this->jobRepository = $jobRepository;
    }

    // TODO[petr]: test response

    /**
     * @Rest\Get("/job")
     * @param Request $request
     * @return Response
     */
    public function getAllFilteringAction(Request $request): Response
    {
        // TODO[petr]: refactor method to use query builder
        return new JsonResponse(
            $this->jobService->findAll($request->query->all()),
            Response::HTTP_OK
        );
    }

    /**
     * @Rest\Get("/job/{id}")
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function getAction($id): Response
    {
        $entity = $this->jobService->find($id);
        if (!$entity) {
            // TODO[petr]: Use ErrorResponse
            throw new NotFoundHttpException(
                sprintf('The resource \'%s\' was not found.', $id)
            );
        }

        return new JsonResponse($entity, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/job")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request): Response
    {
        return $this->validateAndCreate($request->getContent(), Job::class);
    }

    /**
     * @Rest\Put("/job/{id}")
     * @param string  $id
     * @param Request $request
     * @return Response
     */
    public function putAction(string $id, Request $request): Response
    {
        $job = $this->jobRepository->findById($id);
        if (null === $job) {
            return new JsonResponse('Job not found', Response::HTTP_NOT_FOUND);
        }

        return $this->validateAndUpdate(json_encode($job), Job::class);
    }
}
