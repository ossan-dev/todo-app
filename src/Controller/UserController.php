<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function create(Request $request): JsonResponse
    {
        // get payload data
        $parameters = json_decode($request->getContent(), true);

        $entity_manager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setFirstName($parameters['first_name']);
        $user->setLastName($parameters['last_name']);
        $user->setEmail($parameters['email']);

        $entity_manager->persist($user);

        $entity_manager->flush();

        return $this->json(['message' => "The user with id {$user->getId()} has been saved to db!"]);
    }
}
