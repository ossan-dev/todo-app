<?php

namespace App\Service;

class TodoService {
    
    public function get_key_by_id(array $todos, int $id): int 
    {
        $result = 0;
        foreach ($todos as $key => $value) {
            if($value->id == $id)
            $result = $key;
        }

        return $result;
    }
}

?>
