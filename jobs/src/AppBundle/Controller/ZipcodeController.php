<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Services\Zipcode\Zipcode;
use AppBundle\Services\Zipcode\ZipcodeFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class ZipcodeController extends AbstractController
{
    private $zipcodeService;

    private $zipcodeFactory;

    public function __construct(Zipcode $zipcodeService, ZipcodeFactory $zipcodeFactory)
    {
        $this->zipcodeService = $zipcodeService;
        $this->zipcodeFactory = $zipcodeFactory;
    }

    /**
     * @Rest\Get("/zipcode")
     * @return Response
     */
    public function getAllAction(): Response
    {
        return new JsonResponse($this->zipcodeService->findAll(), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/zipcode/{id}")
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function getAction($id): Response
    {
        $entity = $this->zipcodeService->find($id);
        if (!$entity) {
            throw new NotFoundHttpException(
                sprintf('The resource \'%s\' was not found.', $id)
            );
        }

        return new JsonResponse($entity, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/zipcode")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request): Response
    {
        $parameters = $request->request->all();
        $zipcode = $this->zipcodeFactory->create($parameters);
        $persistedEntity = $this->zipcodeService->create($zipcode);

        return new JsonResponse($persistedEntity, Response::HTTP_CREATED);
    }
}
