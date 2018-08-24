<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Form\TaskType;
use App\Service\TaskService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @var TaskService
     */
    private $taskService;

    /**
     * TaskController constructor
     * @param TaskRepository $taskRepository
     * @param TaskService $taskService
     */
    public function __construct(
        TaskRepository $taskRepository,
        TaskService $taskService
    ) {
        $this->taskRepository = $taskRepository;
        $this->taskService = $taskService;
    }

    /**
     * @Route("/ping")
     * @return JsonResponse
     */
//    public function ping(Request $request)
//    {
//        return JsonResponse::create('pong');
//    }

    /**
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(string $id)
    {
        $task = $this->taskRepository->find($id);
        if (null !== $task) {
            $view = $this->view($task, Response::HTTP_OK);
        } else {
//            throw new NotFoundHttpException();
            $view = $this->view(['status' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->handleView($view);
    }

    /**
     * @return Response
     */
    public function cgetAction()
    {
        $view = $this->view(
            $this->taskRepository->findAll()
        );

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(TaskType::class, new Task);
        $form->submit($request->request->all());

        if (false === $form->isValid()) {
            return $this->handleView($this->view($form));
        }

        if ($this->taskService->create($form->getData(), $this->getUser())) {
            return $this->handleView(
                $this->view(['status' => 'ok'], Response::HTTP_CREATED)
            );
        }

        return $this->handleView(
            $this->view(['status' => 'error'], Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(string $id)
    {
        $task = $this->taskRepository->find($id);
        $this->taskService->delete($task);

        $view = $this->view(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function putAction(Request $request, string $id)
    {
        $task = $this->taskRepository->find($id);

        if (null === $task) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->submit($request->request->all());

        if (false === $form->isValid()) {
            return $this->handleView($this->view($form));
        }

        $this->taskService->modify($task);
        $view = $this->view(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }
}
