<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Util\Todo;
use App\Util\CustomException;

class TodoController
{
    // this act as mock for my todos
    private $todos;

    public function __construct()
    {
        $this->todos = array(new Todo(1, "First Todo"), new Todo(2, "Another Todo"), new Todo(3, "Yet Another Todo"));
    }

    // GET: api/todos
    public function index(): JsonResponse
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

        $not_found = new CustomException(404);
        $not_found->add_error("Todo with id $id not found!");
        return new JsonResponse($not_found, $not_found->status_code);
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

        return new JsonResponse($this->todos, 201);
    }
}

?>
