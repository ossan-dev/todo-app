<?php

namespace App\Util;

class Todo {
    public int $id;
    public string $description;
    public bool $isCompleted;

    public function __construct(int $id, string $description, bool $isCompleted = false)
    {
        $this->id = $id;
        $this->description = $description;
        $this->isCompleted = $isCompleted;
    }
}

?>
