<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MessageController.php',
        ]);
    }

    /**
     * @Route("/message/add")
     */
    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        dd($data);
    }
}
