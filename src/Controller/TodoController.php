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
    private $todoService;
    private $logger;

    public function __construct(TodoService $todoService, LoggerInterface $logger)
    {
        $this->todos = [
            1 => new Todo(1, "First Todo"),
            new Todo(2, "Another Todo"),
            new Todo(3, "Yet Another Todo"),
        ];
        $this->todoService = $todoService;
        $this->logger = $logger;
    }

    // GET: api/todos
    public function getAll(): JsonResponse
    {
        return new JsonResponse($this->todos);
    }

    // GET: api/todos/{id:int}
    public function getById(int $id): JsonResponse
    {
        foreach ($this->todos as $key => $value) {
            if($value->id == $id){
                $todo = new Todo($value->id, $value->description, $value->isCompleted);
                return new JsonResponse($todo);
            }
        }
        
        $this->logger->error("Todo with id $id not found!");
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
        $maxId = 0;
        foreach ($this->todos as $key => $value) {
            if($value->id > $maxId)
            $maxId = $value->id;
        }
        
        $parameters = json_decode($request->getContent(), true);
        $this->todos[] = new Todo(++$maxId, $parameters['description']);
        
        return new JsonResponse($this->todos, Response::HTTP_CREATED);
    }
    
    // PUT: api/todos/{id:int}
    public function updateById(int $id, Request $request): JsonResponse
    {
        $keyToReplace = $this->todoService->getKeyById($this->todos, $id);
        
        if($keyToReplace == 0){
            $this->logger->error("Todo with id $id not found!");
            throw new NotFoundHttpException("Todo with id $id not found!");
            
            // TODO: return a custom exception with proper Http status code
            // $not_found = new CustomException(Response::HTTP_NOT_FOUND);
            // $not_found->add_error("Todo with id $id not found!");
            // return new JsonResponse($not_found, $not_found->status_code);
        }
        
        $this->todos[$keyToReplace] = new Todo($id, json_decode($request->getContent(), true)['description']);
        $this->logger->warning("Updated Todo with id $id!");
        
        return new JsonResponse($this->todos[$keyToReplace], Response::HTTP_OK);
    }
    
    // PATCH: api/todos/{id:int}
    public function toggleIsCompleted(int $id) : JsonResponse
    {
        $keyToReplace = $this->todoService->getKeyById($this->todos, $id);
        
        if($keyToReplace == 0){
            $this->logger->error("Todo with id $id not found!");
            throw new NotFoundHttpException("Todo with id $id not found!");
            
            // TODO: return a custom exception with proper Http status code
            // $not_found = new CustomException(Response::HTTP_NOT_FOUND);
            // $not_found->add_error("Todo with id $id not found!");
            // return new JsonResponse($not_found, $not_found->status_code);
        }
        
        $this->todos[$keyToReplace]->isCompleted = !$this->todos[$keyToReplace]->isCompleted;
        $this->logger->warning("Updated Todo with id $id!");
        
        return new JsonResponse($this->todos[$keyToReplace], Response::HTTP_OK);
    }
    
    // DELETE: api/todos/{id:int}
    public function removeById(int $id) : JsonResponse
    {
        $keyToDelete = $this->todoService->getKeyById($this->todos, $id);
        
        if($keyToDelete == 0){
            $this->logger->error("Todo with id $id not found!");
            throw new NotFoundHttpException("Todo with id $id not found!");
            
            // TODO: return a custom exception with proper Http status code
            // $not_found = new CustomException(Response::HTTP_NOT_FOUND);
            // $not_found->add_error("Todo with id $id not found!");
            // return new JsonResponse($not_found, $not_found->status_code);
        }
        
        unset($this->todos[$keyToDelete]);
        $this->logger->warning("Deleted Todo with id $id!");

        $this->todoService->log($this->todos);
        
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}

?>
