<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    // GET: api/users
    public function get_all(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository
                    ->findAll();

        return $this->json($users);
    }

    // GET: api/users/{id:int}
    public function get_by_id(int $id): JsonResponse
    {
        // plain implementation (without repository)
        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($id);

        if(!$user) {
            throw new NotFoundHttpException("The user with id {$id} has not been found in db!");
        }

        return $this->json($user);
    }

    // POST: api/users
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
    
    // PUT: api/users/{id:int}
    public function update_by_id(int $id, Request $request, UserRepository $userRepository) : JsonResponse
    {
        $user = $userRepository
                ->find($id);
        
        if(!$user){
            throw new NotFoundHttpException("The user with id {$id} has not been found in db!");
        }
        
        $parameters = json_decode($request->getContent(), true);
        $user->setFirstName($parameters['first_name']);
        $user->setLastName($parameters['last_name']);
        $user->setEmail($parameters['email']);
        
        $this->getDoctrine()->getManager()->flush();

        return $this->json($user);
    }
}
