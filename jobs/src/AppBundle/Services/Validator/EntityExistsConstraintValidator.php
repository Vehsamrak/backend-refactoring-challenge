<?php

declare(strict_types=1);

namespace AppBundle\Services\Validator;

use AppBundle\Entity\EntityInterface;
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
        if (!$constraint instanceof EntityExistsConstraint) {
            throw new UnexpectedTypeException($constraint, EntityExistsConstraint::class);
        }

        if (null === $entityId || '' === $entityId) {
            return;
        }

        $entityClassName = $constraint->entityClassName;

        if (!$this->isEntity($entityClassName)) {
            throw new UnexpectedTypeException($entityClassName, EntityInterface::class);
        }

        $repository = $this->entityManager->getRepository($entityClassName);
        if (null === $repository) {
            throw new UnexpectedTypeException($repository, ObjectRepository::class);
        }

        if ($this->entityExists($repository, $entityId, $constraint->exists)) {
            $message = $constraint->exists
                ? $constraint->messageNotFound
                : $constraint->messageFound;

            $this->context
                ->buildViolation($message)
                ->setParameter('{{ name }}', ucfirst($constraint->name))
                ->setParameter('{{ value }}', $entityId)
                ->addViolation();
        }
    }

    private function isEntity(?string $entityClassName): bool
    {
        if (!class_exists((string) $entityClassName)) {
        	return false;
        }

        return in_array(EntityInterface::class, class_implements($entityClassName), true);
    }

    private function entityExists(ObjectRepository $repository, $entityId, bool $exists): bool
    {
        $entity = $repository->find($entityId);

        return $exists
            ? null === $entity
            : null !== $entity;
    }
}
