<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // get payload data
        $parameters = json_decode($request->getContent(), true);

        $entity_manager = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setFirstName($parameters['first_name']);
        $user->setLastName($parameters['last_name']);
        $user->setEmail($parameters['email']);

        // check if the input is correct
        $errors = $validator->validate($user);
        if(count($errors) > 0){
            throw new BadRequestException((string)$errors);
        }

        $entity_manager->persist($user);
        $entity_manager->flush();

        return $this->json(['message' => "The user with id {$user->getId()} has been saved to db!"]);
    }
}
