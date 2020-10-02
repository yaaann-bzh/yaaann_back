<?php

namespace App\Controller;

use App\Entity\Project;
use App\Exception\ResourceValidationException;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @Route("/api")
 */
class ProjectController extends AbstractFOSRestController
{
    /**
     * @Route(
     *      path = "/projects",
     *      name = "projects_list",
     *      methods={"GET"}
     * )
     */
    public function listAction()
    {
        $projects = $this->getDoctrine()
                ->getRepository(Project::class)
                ->findAll();

        $context = new Context();

        $view = $this->view($projects, 200);
        $view->setContext($context->setGroups(array('projects', 'users', 'cathegories')));

        return $this->handleView($view);
    }

    /**
     * @Route(
     *      path = "/projects/{project_id}",
     *      name = "projects_show",
     *      requirements = {"id"="\d+"},
     *      methods={"GET"}
     * )
     * 
     * @ParamConverter("project", options={"id" = "project_id"})
     */
    public function showAction(Project $project)
    {
        $context = new Context();

        $view = $this->view($project, 200);
        $view->setContext($context->setGroups(array('project_detail', 'users', 'cathegories')));

        return $this->handleView($view);
    }

    /**
     * @Route(
     *      path = "/admin/projects",
     *      name = "projects_add",
     *      methods={"POST"}
     * )
     * 
     * @ParamConverter("project", converter="fos_rest.request_body")
     */
    public function createAction(Project $project, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        $context = new Context();

        $view = $this->view($project, Response::HTTP_CREATED);
        $view->setContext($context->setGroups(array('project_detail', 'users', 'cathegories')));

        return $this->handleView($view);  
    }

    /**
     * @Route(
     *      path = "/admin/projects/{project_id}",
     *      name = "projects_edit",
     *      requirements = {"id"="\d+"},
     *      methods={"PUT"}
     * )
     * 
     * @ParamConverter("project", options={"id" = "project_id"})
     * @ParamConverter("updated", converter="fos_rest.request_body")
     */
    public function editAction(Project $project, Project $updated, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }

        $project->setUrl($updated->getUrl());
        $project->setGithubUrl($updated->getGithubUrl());
        $project->setGitlabUrl($updated->getGitlabUrl());
        $project->setPictures($updated->getPictures());
        $project->setTitle($updated->getTitle());
        $project->setShortTitle($updated->getShortTitle());
        $project->setTldr($updated->getTldr());
        $project->setContent($updated->getContent());
        $project->setCathegory($updated->getCathegory());

        $project->setUpdatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->flush();
            
        $context = new Context();

        $view = $this->view($project, Response::HTTP_OK);
        $view->setContext($context->setGroups(array('project_detail', 'users', 'cathegories')));

        return $this->handleView($view);  
    }

    /**
     * @Route(
     *      path = "/admin/projects/{project_id}",
     *      name = "projects_delete",
     *      requirements = {"id"="\d+"},
     *      methods={"DELETE"}
     * )
     * 
     * @ParamConverter("project", options={"id" = "project_id"})
     */
    public function deleteAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        $response = new Response('Projet supprimé', Response::HTTP_NO_CONTENT);
        return $response;         
    }
}
