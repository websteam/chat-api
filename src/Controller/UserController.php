<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/user", name="user", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return $this->json($users);
    }

    /**
     * @Route("/user/login", name="login", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'])) {
            throw new BadRequestHttpException('Email parameter is mandatory');
        }

        // Logging in using email creates new user or returns one from database
        $user = $this->userRepository->loginOrCreate($data['email']);

        return $this->json($user);
    }

    /**
     * @Route("/user/refresh_token", name="refresh_token")
     */
    public function refreshToken(Request $request): JsonResponse
    {
        return $this->json('test');
    }
}
