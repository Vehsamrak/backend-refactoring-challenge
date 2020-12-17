<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Services\JobCategory\JobCategoryFactory;
use AppBundle\Services\JobCategory\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class ServiceController extends AbstractController
{
    private $serviceService;

    private $jobCategoryFactory;

    public function __construct(Service $serviceService, JobCategoryFactory $jobCategoryFactory)
    {
        $this->serviceService = $serviceService;
        $this->jobCategoryFactory = $jobCategoryFactory;
    }

    /**
     * @Rest\Get("/service")
     * @return Response
     */
    public function getAllAction(): Response
    {
        $all = $this->serviceService->findAll();

        return new JsonResponse($all, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/service/{id}")
     * @param int id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function getAction($id): Response
    {
        // TODO[petr]: rename entity
        $entity = $this->serviceService->find($id);

        if (!$entity) {
            throw new NotFoundHttpException(
                sprintf('The resource \'%s\' was not found.', $id)
            );
        }

        return new JsonResponse($entity, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/service")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request): Response
    {
        $parameters = $request->request->all();
        $jobCategory = $this->jobCategoryFactory->create($parameters);
        $persistedEntity = $this->serviceService->create($jobCategory);

        return new JsonResponse($persistedEntity, Response::HTTP_CREATED);
    }
}
