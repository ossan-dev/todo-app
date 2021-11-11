<?php

namespace App\Service;
use Psr\Log\LoggerInterface;

class TodoService {

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function getKeyById(array $todos, int $id): int 
    {
        $result = 0;
        foreach ($todos as $key => $value) {
            if($value->id == $id)
            $result = $key;
        }

        return $result;
    }

    public function log(array $todos): void
    {
        $this->logger->info('Remaining Todos:');
        foreach ($todos as $key => $value) {
            $this->logger->info(json_encode($value));
        }
    }
}

?>
