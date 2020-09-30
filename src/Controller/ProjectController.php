<?php

namespace App\Controller;

use App\Entity\Project;
use App\Exception\ResourceValidationException;
use DateTime;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

class ProjectController extends AbstractFOSRestController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer) 
    {
        $this->serializer = $serializer;
    }

    /**
     * @Rest\Get(
     *      path = "/projects",
     *      name = "projects_list"
     * )
     */
    public function listAction()
    {
        $projects = $this->getDoctrine()
                ->getRepository(Project::class)
                ->findAll();

        $data = $this->serializer->serialize($projects, 'json', SerializationContext::create()->setGroups(array('projects', 'users', 'cathegories')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response; 
    }

    /**
     * @Rest\Get(
     *      path = "/projects/{project_id}",
     *      name = "projects_show",
     *      requirements = {"id"="\d+"}
     * )
     * 
     * @ParamConverter("project", options={"id" = "project_id"})
     */
    public function showAction(Project $project)
    {
        $view = $this->view($project, 200);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post(
     *      path = "/projects",
     *      name = "projects_add"
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
            
        return $this->handleView($this->view($project, Response::HTTP_CREATED));  
    }

    /**
     * @Rest\Put(
     *      path = "/projects/{project_id}",
     *      name = "projects_edit",
     *      requirements = {"id"="\d+"}
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
            
        return $this->handleView($this->view($project, Response::HTTP_OK));  
    }

    /**
     * @Rest\Delete(
     *      path = "/projects/{project_id}",
     *      name = "projects_edit",
     *      requirements = {"id"="\d+"}
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
