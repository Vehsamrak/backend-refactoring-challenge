<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractController extends AbstractFOSRestController
{
    /**
     * @var String
     */
    protected $serviceName;

    /**
     * @var String
     */
    protected $builder;

    /**
     * @deprecated
     */
    public function getAllAction()
    {
        return new View(
            $this->container->get($this->serviceName)->findAll(),
            Response::HTTP_OK
        );
    }

    /**
     * @param $id
     * @deprecated
     */
    public function getAction($id)
    {
        $entity = $this->container->get($this->serviceName)->find($id);
        if (!$entity) {
            throw new NotFoundHttpException(sprintf(
                'The resource \'%s\' was not found.',
                $id
            ));
        }

        return new View(
            $entity,
            Response::HTTP_OK
        );
    }

    /**
     * @param Request $request
     * @deprecated
     */
    public function postAction(Request $request)
    {
        $parameters = $request->request->all();
        $entity = $this->builder::build($parameters);
        $persistedEntity = $this->container->get($this->serviceName)->create($entity);

        return new View(
            $persistedEntity,
            Response::HTTP_CREATED
        );
    }
}
