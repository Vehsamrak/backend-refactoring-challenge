<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Dto\ValidationErrorResponse;
use AppBundle\Entity\EntityInterface;
use AppBundle\Exception\ClassNotFoundException;
use AppBundle\Exception\InterfaceException;
use AppBundle\Repository\SearchParametersInterface;
use AppBundle\Repository\SearchRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Exception\RuntimeException as JMSRuntimeException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractController
{
    private $serializer;

    private $entityManager;

    private $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param string $entityDataJson
     * @param string $entityClassName
     * @return JsonResponse
     * @throws ClassNotFoundException
     */
    protected function validateAndCreate(string $entityDataJson, string $entityClassName): JsonResponse
    {
        return $this->validateAndUpsert($entityDataJson, $entityClassName, true);
    }

    /**
     * @param array  $searchParametersData
     * @param string $searchParametersClassname
     * @return JsonResponse
     * @throws ClassNotFoundException
     */
    protected function validateAndSearch(array $searchParametersData, string $searchParametersClassname): JsonResponse
    {
        $this->checkSearchParameters($searchParametersClassname);

        try {
            /** @var SearchParametersInterface $searchParameters */
            $searchParameters = $this->serializer->deserialize(
                json_encode($searchParametersData),
                $searchParametersClassname,
                'json'
            );
        } catch (JMSRuntimeException $JMSException) {
            return new JsonResponse('JSON is malformed', Response::HTTP_BAD_REQUEST);
        }

        $validationErrors = $this->validator->validate($searchParameters);

        if (count($validationErrors) > 0) {
            return new ValidationErrorResponse($validationErrors);
        }

        $repository = $this->entityManager->getRepository($searchParameters->getEntityClassName());
        if (!$repository instanceof SearchRepositoryInterface) {
            throw new \InvalidArgumentException(
                sprintf('Repository must implement with %s', SearchRepositoryInterface::class)
            );
        }

        return new JsonResponse($repository->findAllByParameters($searchParameters), Response::HTTP_OK);
    }

    /**
     * @param string $entityDataJson
     * @param string $entityClassName
     * @return JsonResponse
     * @throws ClassNotFoundException
     */
    protected function validateAndUpdate(string $entityDataJson, string $entityClassName): JsonResponse
    {
        return $this->validateAndUpsert($entityDataJson, $entityClassName, false);
    }

    /**
     * @param string $entityDataJson
     * @param string $entityClassName
     * @param bool   $isEntityNew
     * @return JsonResponse
     */
    protected function validateAndUpsert(
        string $entityDataJson,
        string $entityClassName,
        bool $isEntityNew
    ): JsonResponse {
        $this->checkEntityClass($entityClassName);

        try {
            $entity = $this->serializer->deserialize($entityDataJson, $entityClassName, 'json');
        } catch (JMSRuntimeException $JMSException) {
            return new JsonResponse('JSON is malformed', Response::HTTP_BAD_REQUEST);
        }

        $validationErrors = $this->validator->validate($entity);
        if (count($validationErrors) > 0) {
            return new ValidationErrorResponse($validationErrors);
        }

        if ($isEntityNew) {
            $this->entityManager->persist($entity);
            $returnCode = Response::HTTP_CREATED;
        } else {
            $this->entityManager->merge($entity);
            $returnCode = Response::HTTP_OK;
        }

        $this->entityManager->flush();

        return new JsonResponse($entity, $returnCode);
    }

    private function checkEntityClass(string $entityClassName): void
    {
        if (!class_exists($entityClassName)) {
            throw new ClassNotFoundException($entityClassName);
        }

        if (!in_array(EntityInterface::class, class_implements($entityClassName), true)) {
            throw new InterfaceException($entityClassName, EntityInterface::class);
        }
    }

    private function checkSearchParameters(string $className): void
    {
        if (!class_exists($className)) {
            throw new ClassNotFoundException($className);
        }

        if (!in_array(SearchParametersInterface::class, class_implements($className), true)) {
            throw new InterfaceException($className, SearchParametersInterface::class);
        }
    }
}
