<?php

namespace App\Controller;

use App\Entity\Project;
use App\Exception\ResourceValidationException;
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
    
}
