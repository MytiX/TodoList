<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    public function __construct(private TaskRepository $taskRepository, private EntityManagerInterface $entityManager) {}

    #[Route(path: '/tasks', name: 'task_list')]
    public function listAction(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN', $user)) {
            $tasks = $this->taskRepository->findAllAnonymousAndTheseTask($user->getId());
        } else {
            $tasks = $this->taskRepository->findBy([
                'user' => $user->getId()
            ]);
        }

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route(path: '/tasks/create', name: 'task_create')]
    public function createAction(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());

            $this->taskRepository->save($task, true);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route(path: '/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(int $id, Request $request): Response
    {
        $task = $this->taskRepository->find($id);

        if (null === $task) {
            return $this->redirectToRoute('task_list');
        }

        $this->denyAccessUnlessGranted('EDIT', $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route(path: '/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(int $id): Response
    {
        $task = $this->taskRepository->find($id);

        if (null === $task) {
            return $this->redirectToRoute('task_list');
        }

        $this->denyAccessUnlessGranted('TOGGLE', $task);

        $task->toggle(!$task->isDone());
        $this->entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route(path: '/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(int $id): Response
    {
        $task = $this->taskRepository->find($id);

        if (null === $task) {
            return $this->redirectToRoute('task_list');
        }

        $this->denyAccessUnlessGranted('DELETE', $task);

        $this->taskRepository->remove($task, true);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
