<?php

namespace App\Responses;

class ApiSuccess extends ApiResponse implements ApiResponseInterface
{
    public function __construct(string $message, $data=[])
    {
        parent::__construct(parent::STATUS_SUCCESS, $message, $data);
    }
}
