<?php

namespace App\Controller;

use App\Entity\ProjectCathegory;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ProjectCathegoryController extends AbstractFOSRestController
{
    /**
     * @Route(
     *      path = "/cathegories-simple",
     *      name = "cathegories_list_simple",
     *      methods={"GET"}
     * )
     */
    public function simpleListAction()
    {
        $cathegories = $this->getDoctrine()
                ->getRepository(ProjectCathegory::class)
                ->findAll();
        
        $context = new Context();

        $view = $this->view($cathegories, 200);
        $view->setContext($context->setGroups(array('cathegories')));

        return $this->handleView($view);
    }

    /**
     * @Route(
     *      path = "/cathegories-detail",
     *      name = "cathegories_list_detail",
     *      methods={"GET"}
     * )
     */
    public function detailListAction()
    {
        $cathegories = $this->getDoctrine()
                ->getRepository(ProjectCathegory::class)
                ->findAll();
        
        $context = new Context();

        $view = $this->view($cathegories, 200);
        $view->setContext($context->setGroups(array('cathegory_detail','projects')));

        return $this->handleView($view);
    }
}
