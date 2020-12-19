<?php

declare(strict_types=1);

namespace AppBundle\Services\Validator;

use AppBundle\Entity\EntityInterface;
use AppBundle\Exception\ClassNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EntityExistsConstraintValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string|int $entityId
     * @param Constraint $constraint
     */
    public function validate($entityId, Constraint $constraint): void
    {
        return;
        if (!$constraint instanceof EntityExistsConstraint) {
            throw new UnexpectedTypeException($constraint, EntityExistsConstraint::class);
        }

        if (null === $entityId || '' === $entityId) {
            return;
        }

        $entityClassName = $constraint->entityClassName;
        if (null === $entityClassName) {
            throw new UnexpectedTypeException($entityClassName, EntityInterface::class);
        }

        if (!class_exists($entityClassName)) {
            throw new ClassNotFoundException($entityClassName);
        }

        if (!$this->isEntity($entityClassName)) {
            throw new UnexpectedTypeException($entityClassName, EntityInterface::class);
        }

        $repository = $this->entityManager->getRepository($entityClassName);
        if (null === $repository) {
            throw new UnexpectedTypeException($repository, ObjectRepository::class);
        }

        if (!$this->entityExists($repository, $entityId)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ name }}', ucfirst($constraint->name))
                ->setParameter('{{ value }}', $entityId)
                ->addViolation();
        }
    }

    private function isEntity(string $entityClassName): bool
    {
        return in_array(EntityInterface::class, class_implements($entityClassName), true);
    }

    private function entityExists(ObjectRepository $repository, $entityId): bool
    {
        $entity = $repository->find($entityId);

        return null !== $entity;
    }
}
