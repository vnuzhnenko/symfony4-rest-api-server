<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteResource("Task")
 */
class TaskController extends FOSRestController
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * TaskController constructor.
     */
    public function __construct(
        TaskRepository $taskRepository
    ) {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Annotations\Get(path="/task/{id}")
     * @return JsonResponse
     */
//    public function ping()
//    {
//        return new JsonResponse('pong');
//    }


    /**
     * @param $id int
     * @return JsonResponse
     */
    public function getAction($id)
    {
        return JsonResponse::create($this->taskRepository->find($id), JsonResponse::HTTP_OK);
    }

    /**
     * @param Request $request
     */
    public function postAction(Request $request)
    {
    }

    /**
     * @param Request $request
     */
    public function deleteAction(Request $request)
    {
    }

    /**
     * @param Request $request
     */
    public function putAction(Request $request)
    {
    }
}
