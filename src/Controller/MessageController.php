<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private $messageRepository;

    private $userRepository;

    public function __construct(MessageRepository $messageRepository, UserRepository $userRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/message", name="message", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $messages = $this->messageRepository->findAll();

        return $this->json($messages);
    }

    /**
     * @Route("/message", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $user = $this->userRepository->find($request->get('user_id'));

        if (is_null($user)) {
            throw new ConflictHttpException('Wrong message user persisted');
        }

        // For now room id is set to 1 due to possibility for extension in future
        $message = $this->messageRepository->create($request->get('content'), $user, 1);

        return $this->json($message);
    }
}
