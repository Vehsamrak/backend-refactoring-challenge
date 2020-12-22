<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Dto;
use AppBundle\Exception\ClassNotFoundException;
use AppBundle\Exception\InterfaceException;
use AppBundle\Repository\SearchParametersInterface;
use AppBundle\Repository\SearchRepositoryInterface;
use AppBundle\Services\EntityFactory\AbstractEntityFactory;
use AppBundle\Services\EntityFactory\EntityAwareInterface;
use AppBundle\Services\EntityUpdater\AbstractEntityUpdater;
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

    private $entityFactory;

    private $entityUpdater;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AbstractEntityFactory $entityFactory,
        AbstractEntityUpdater $entityUpdater
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->entityFactory = $entityFactory;
        $this->entityUpdater = $entityUpdater;
    }

    /**
     * @param array  $searchParametersData
     * @param string $searchParametersClassname
     * @return JsonResponse
     * @throws ClassNotFoundException
     * @throws InterfaceException
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
            return new Dto\ValidationErrorResponse($validationErrors);
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
     * @param string          $entityDataJson
     * @param string          $entityAwareClassName
     * @param string|int|null $entityId
     * @return JsonResponse
     * @throws ClassNotFoundException
     * @throws InterfaceException
     */
    protected function validateAndUpsert(
        string $entityDataJson,
        string $entityAwareClassName,
        $entityId = null
    ): JsonResponse {
        $this->checkEntityAwareClass($entityAwareClassName);

        try {
            /** @var EntityAwareInterface $entityAware */
            $entityAware = $this->serializer->deserialize($entityDataJson, $entityAwareClassName, 'json');
        } catch (JMSRuntimeException $JMSException) {
            return new JsonResponse('JSON is malformed', Response::HTTP_BAD_REQUEST);
        }

        $validationErrors = $this->validator->validate($entityAware);
        if (count($validationErrors) > 0) {
            return new Dto\ValidationErrorResponse($validationErrors);
        }

        if (null === $entityId) {
            $entity = $this->entityFactory->create($entityAware);
            $returnCode = Response::HTTP_CREATED;
        } else {
            $entity = $this->entityUpdater->update($entityId, $entityAware);
            $returnCode = Response::HTTP_OK;
        }

        return new JsonResponse($entity, $returnCode);
    }

    /**
     * @param string $entityAwareClassName
     * @throws ClassNotFoundException
     * @throws InterfaceException
     */
    private function checkEntityAwareClass(string $entityAwareClassName): void
    {
        $this->checkInstanceOf($entityAwareClassName, EntityAwareInterface::class);
    }

    /**
     * @param string $className
     * @throws ClassNotFoundException
     * @throws InterfaceException
     */
    private function checkSearchParameters(string $className): void
    {
        $this->checkInstanceOf($className, SearchParametersInterface::class);
    }

    /**
     * @param string $className
     * @param string $interfaceName
     * @throws ClassNotFoundException
     * @throws InterfaceException
     */
    private function checkInstanceOf(string $className, string $interfaceName): void
    {
        if (!class_exists($className)) {
            throw new ClassNotFoundException($className);
        }

        if (!in_array($interfaceName, class_implements($className), true)) {
            throw new InterfaceException($className, $interfaceName);
        }
    }
}
