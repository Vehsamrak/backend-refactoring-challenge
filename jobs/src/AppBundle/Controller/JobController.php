<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Dto;
use AppBundle\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class JobController extends AbstractController
{
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
     * @ParamConverter(name="id", class="AppBundle\Entity\Job")
     * @param Job $job
     * @return Response
     */
    public function getAction(Job $job): Response
    {
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
     * @ParamConverter(name="id", class="AppBundle\Entity\Job")
     * @param Job     $job
     * @param Request $request
     * @return Response
     */
    public function putAction(Job $job, Request $request): Response
    {
        return $this->validateAndUpsert($request->getContent(), Dto\UpdateJobRequest::class, $job->getId());
    }
}
