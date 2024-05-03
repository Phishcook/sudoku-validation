<?php

namespace Domain\View;

use Symfony\Component\HttpFoundation\Response;

class SudokuResponse extends Response

{
    public function __construct(private readonly bool $isValid, private readonly ?string $message = '')
    {
        parent::__construct($this);
    }

    public function __toString(): string
    {
        return json_encode([
            'valid' => $this->isValid,
            'message' => $this->message
        ]);
    }
}