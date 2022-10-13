<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function indexAction(TaskRepository $taskRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN', $user)) {
            $tasks = $taskRepository->findAllAnonymousAndTheseTask($user->getId());
        } else {
            $tasks = $taskRepository->findBy([
                'user' => $user->getId(),
                'isDone' => 0
            ]);
        }

        return $this->render('default/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
