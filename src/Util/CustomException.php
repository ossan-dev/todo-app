<?php

namespace App\Util;

class CustomException {
    public int $status_code;
    // TODO: change access modifier in order to prevent direct change on this prop
    public $data;

    public function __construct(int $status_code = 500)
    {
        $this->status_code = $status_code;
        $this->data = array();
    }

    public function add_error(string $message) : void
    {
        array_push($this->data, $message);
    }

}

?>
