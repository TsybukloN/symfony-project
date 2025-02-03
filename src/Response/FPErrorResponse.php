<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;

class FPErrorResponse extends Response
{
    public function __construct(string $message, int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $data = [
            'error' => true,
            'message' => $message,
            'error_code' => $errorCode ?? 0,
            'timestamp' => (new \DateTime())->format(\DateTime::ATOM),
        ];

        parent::__construct(json_encode($data), $statusCode, ['Content-Type' => 'application/json']);
    }
}
