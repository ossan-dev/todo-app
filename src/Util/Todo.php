<?php

namespace App\Util;

class Todo {
    public int $id;
    public string $description;
    public bool $is_completed;

    public function __construct(int $id, string $description, bool $is_completed = false)
    {
        $this->id = $id;
        $this->description = $description;
        $this->is_completed = $is_completed;
    }
}

?>
