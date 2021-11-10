<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Util\Todo;
// use App\Util\CustomException;
use App\Service\TodoService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Psr\Log\LoggerInterface;

class TodoController
{
    // this act as mock for my todos
    private $todos;
    private $todo_service;
    private $logger;

    public function __construct(TodoService $todo_service, LoggerInterface $logger)
    {
        $this->todos = [1 => new Todo(1, "First Todo"), new Todo(2, "Another Todo"), new Todo(3, "Yet Another Todo")];
        $this->todo_service = $todo_service;
        $this->logger = $logger;
    }

    // GET: api/todos
    public function get_all(): JsonResponse
    {
        return new JsonResponse($this->todos);
    }

    // GET: api/todos/{id:int}
    public function get_by_id(int $id): JsonResponse
    {
        foreach ($this->todos as $key => $value) {
            if($value->id == $id){
                $todo = new Todo($value->id, $value->description, $value->is_completed);
                return new JsonResponse($todo);
            }
        }
        
        throw new NotFoundHttpException("Todo with id $id not found!");
    
        // TODO: return a custom exception with proper Http status code
        // $not_found = new CustomException(Response::HTTP_NOT_FOUND);
        // $not_found->add_error("Todo with id $id not found!");
        // return new JsonResponse($not_found, $not_found->status_code);
    }

    // POST: api/todos
    public function create(Request $request): JsonResponse
    {
        // get max id of $todos
        $max_id = 0;
        foreach ($this->todos as $key => $value) {
            if($value->id > $max_id)
                $max_id = $value->id;
        }

        $parameters = json_decode($request->getContent(), true);
        $this->todos[] = new Todo(++$max_id, $parameters['description']);

        return new JsonResponse($this->todos, Response::HTTP_CREATED);
    }
    
    // PUT: api/todos/{id:int}
    public function update_by_id(int $id, Request $request): JsonResponse
    {
        $key_to_replace = $this->todo_service->get_key_by_id($this->todos, $id);
        
        if($key_to_replace == 0){
            throw new NotFoundHttpException("Todo with id $id not found!");

            // TODO: return a custom exception with proper Http status code
            // $not_found = new CustomException(Response::HTTP_NOT_FOUND);
            // $not_found->add_error("Todo with id $id not found!");
            // return new JsonResponse($not_found, $not_found->status_code);
        }
        
        $this->todos[$key_to_replace] = new Todo($id, json_decode($request->getContent(), true)['description']);
        
        return new JsonResponse($this->todos[$key_to_replace], Response::HTTP_OK);
    }
    
    // PATCH: api/todos/{id:int}
    public function toggle_is_completed(int $id) : JsonResponse
    {
        $key_to_replace = $this->todo_service->get_key_by_id($this->todos, $id);
        
        if($key_to_replace == 0){
            throw new NotFoundHttpException("Todo with id $id not found!");

            // TODO: return a custom exception with proper Http status code
            // $not_found = new CustomException(Response::HTTP_NOT_FOUND);
            // $not_found->add_error("Todo with id $id not found!");
            // return new JsonResponse($not_found, $not_found->status_code);
        }
        
        $this->todos[$key_to_replace]->is_completed = !$this->todos[$key_to_replace]->is_completed;

        return new JsonResponse($this->todos[$key_to_replace], Response::HTTP_OK);
    }
}

?>
