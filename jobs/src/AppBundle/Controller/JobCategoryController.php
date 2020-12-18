<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\JobCategory;
use AppBundle\Services\JobCategory\Service;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JobCategoryController extends AbstractController
{
    private $jobCategoryService;

    public function __construct(
        Service $jobCategoryService,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        parent::__construct($entityManager, $serializer, $validator);
        $this->jobCategoryService = $jobCategoryService;
    }

    // TODO[petr]: rename api path to category
    /**
     * @Rest\Get("/service")
     * @return Response
     */
    public function getAllAction(): Response
    {
        $all = $this->jobCategoryService->findAll();

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
        $entity = $this->jobCategoryService->find($id);

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
        return $this->validateAndSave($request, JobCategory::class);
    }
}
