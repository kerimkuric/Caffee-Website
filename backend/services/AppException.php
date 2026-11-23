<?php

class AppException extends Exception
{
    public function __construct(
        string $message,
        private int $status = 400
    ) {
        parent::__construct($message, $status);
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
