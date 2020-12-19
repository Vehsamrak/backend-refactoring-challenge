<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Dto\ValidationErrorResponse;
use AppBundle\Entity\EntityInterface;
use AppBundle\Exception\ClassNotFoundException;
use AppBundle\Exception\NotEntityException;
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
        if (!class_exists($entityClassName)) {
            throw new ClassNotFoundException($entityClassName);
        }

        if (!$this->isEntity($entityClassName)) {
            throw new NotEntityException($entityClassName);
        }
        

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

        try {
            $this->entityManager->flush();
        } catch (\Throwable $exception) {
            // TODO[petr]: remove dump
            var_dump($exception->getMessage());
            die;
        }
        
        return new JsonResponse($entity, $returnCode);
    }

    private function isEntity(string $entityClassName): bool
    {
        return in_array(EntityInterface::class, class_implements($entityClassName), true);
    }
}
