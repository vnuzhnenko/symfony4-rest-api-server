<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a new Task
     * @param Task $task
     * @return bool
     */
    public function create(Task $task, User $user): bool
    {
        try {
            $task->setUser($user);
            $task->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($task);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
        return true;
    }

    /**
     * Creates a new Task
     * @param Task $task
     * @return bool
     */
    public function modify(Task $task): bool
    {
        try {
            // Something useful logic can be here
            $this->entityManager->flush();
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
        return true;
    }

    /**
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool
    {
        try {
            $this->entityManager->remove($task);
            $this->entityManager->flush($task);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
        return true;
    }
}