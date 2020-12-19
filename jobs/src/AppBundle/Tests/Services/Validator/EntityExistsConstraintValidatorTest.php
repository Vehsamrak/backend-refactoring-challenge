<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services\Validator;

use AppBundle\Entity\EntityInterface;
use AppBundle\Exception\ClassNotFoundException;
use AppBundle\Services\Validator\EntityExistsConstraint;
use AppBundle\Services\Validator\EntityExistsConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

/**
 * @group unit
 */
class EntityExistsConstraintValidatorTest extends TestCase
{
    private const ENTITY_EXISTING_ID = 1;
    private const ENTITY_UNEXISTING_ID = 2;

    /**
     * @test
     * @dataProvider provideEntityIdentifiers
     * @param             $value
     * @param bool        $expectedHasValidationErrors
     */
    public function validate_GivenEntityIdentifier_MustReturnPropperError(
        $value,
        bool $expectedHasValidationErrors
    ): void {
        $entity = $this->createMock(EntityInterface::class);
        $constraint = $this->createConstraint(get_class($entity));
        $validator = $this->createValidator();
        $executionContext = $this->createExecutionContext($validator, $constraint);

        $validator->validate($value, $constraint);

        $this->assertSame($expectedHasValidationErrors, $executionContext->getViolations()->count() > 0);
    }

    /**
     * @test
     * @dataProvider provideEntityClasses
     * @param string|null $entityClassName
     * @param string|null $expectedExceptionClassName
     */
    public function validate_GivenConstraintWithoutRepositoryClass_ThrowsExceptionIfRepositoryClassIsInvalid(
        ?string $entityClassName,
        ?string $expectedExceptionClassName
    ): void {
        $constraint = $this->createConstraint($entityClassName);
        $validator = $this->createValidator();
        $validValue = null;
        $exception = null;

        try {
            $validator->validate($validValue, $constraint);
        } catch (Throwable $exception) {
        }

        $this->assertException($expectedExceptionClassName, $exception);
    }

    public function provideEntityIdentifiers(): array
    {
        return [
            'valid: value is null' => [null, false],
            'valid: value is empty string' => ['', false],
            'valid: entity exists' => [self::ENTITY_EXISTING_ID, false],
            'invalid: entity not exists' => [self::ENTITY_UNEXISTING_ID, true],
        ];
    }

    public function provideEntityClasses(): array
    {
        $entity = $this->createMock(EntityInterface::class);

        return [
            'entity class is null. Throws exception' => [null, UnexpectedTypeException::class],
            'entity class not found. Throws exception' => ['', ClassNotFoundException::class],
            'entity class is not entity. Throws exception' => [self::class, UnexpectedTypeException::class],
            'entity class is entity. No exceptions expected' => [get_class($entity), null],
        ];
    }

    private function createValidator(): ConstraintValidatorInterface
    {
        /** @var EntityManagerInterface|PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createConfiguredMock(
            EntityManagerInterface::class,
            ['getRepository' => $this->createRepository()]
        );

        return new EntityExistsConstraintValidator($entityManager);
    }

    private function createExecutionContext(
        ConstraintValidatorInterface $validator,
        Constraint $constraint
    ): ExecutionContext {
        /** @var ValidatorInterface $contextValidator */
        $contextValidator = $this->createMock(ValidatorInterface::class);
        /** @var TranslatorInterface $translator */
        $translator = $this->createMock(TranslatorInterface::class);

        $executionContext = new ExecutionContext($contextValidator, 0, $translator);
        $executionContext->setConstraint($constraint);
        $validator->initialize($executionContext);

        return $executionContext;
    }

    private function createConstraint(?string $entityClassName): EntityExistsConstraint
    {
        $entityExistsConstraint = new EntityExistsConstraint();
        $entityExistsConstraint->entityClassName = $entityClassName;

        return $entityExistsConstraint;
    }

    private function createRepository(): ObjectRepository
    {
        /** @var EntityInterface|PHPUnit_Framework_MockObject_MockObject $entity */
        $entity = $this->createMock(EntityInterface::class);

        /** @var ObjectRepository|PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = $this->createMock(ObjectRepository::class);
        $repositoryCallback = static function ($id) use ($entity): ?EntityInterface {
            if (self::ENTITY_EXISTING_ID === $id) {
                return $entity;
            }

            return null;
        };

        $repository
            ->method('find')
            ->willReturnCallback($repositoryCallback);

        return $repository;
    }

    private function assertException(?string $expectedExceptionClassName, ?Throwable $exception): void
    {
        if (null === $expectedExceptionClassName) {
            $this->assertNull($exception);
        } else {
            $this->assertInstanceOf($expectedExceptionClassName, $exception);
        }
    }
}
