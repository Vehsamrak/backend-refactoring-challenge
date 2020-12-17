<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Services\Job\Job;
use AppBundle\Services\Job\JobFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class JobController extends AbstractController
{
    private $jobService;

    private $jobFactory;

    public function __construct(Job $jobService, JobFactory $jobFactory)
    {
        $this->jobService = $jobService;
        $this->jobFactory = $jobFactory;
    }

    /**
     * @Rest\Get("/job")
     * @param Request $request
     * @return Response
     */
    public function getAllFilteringAction(Request $request): Response
    {
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
        $parameters = $request->request->all();
        $job = $this->jobFactory->create($parameters);
        $persistedEntity = $this->jobService->create($job);

        return new JsonResponse($persistedEntity, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("/job/{id}")
     * @param string  $id
     * @param Request $request
     * @return Response
     */
    public function putAction(string $id, Request $request): Response
    {
        $params = $request->request->all();
        $params['id'] = $id;
        $job = $this->jobFactory->create($params);
        $persistedEntity = $this->jobService->update($job);

        return new JsonResponse($persistedEntity, Response::HTTP_OK);
    }
}
