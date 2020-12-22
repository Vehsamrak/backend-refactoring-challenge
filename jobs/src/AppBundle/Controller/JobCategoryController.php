<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Dto\UpdateJobCategoryRequest;
use AppBundle\Entity\JobCategory;
use AppBundle\Repository\JobCategoryRepository;
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

class JobCategoryController extends AbstractController
{
    private $jobCategoryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        JobCategoryRepository $jobCategoryRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AbstractEntityFactory $entityFactory,
        AbstractEntityUpdater $entityUpdater
    ) {
        parent::__construct($entityManager, $serializer, $validator, $entityFactory, $entityUpdater);
        $this->jobCategoryRepository = $jobCategoryRepository;
    }

    /**
     * @Rest\Get("/category")
     * @return Response
     */
    public function getAllAction(): Response
    {
        return new JsonResponse($this->jobCategoryRepository->findAll(), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/category/{id}")
     * @param JobCategory $category
     * @return Response
     * @ParamConverter(name="id", class="AppBundle\Entity\JobCategory")
     */
    public function getAction(JobCategory $category): Response
    {
        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/category")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request): Response
    {
        return $this->validateAndUpsert($request->getContent(), UpdateJobCategoryRequest::class);
    }
}
