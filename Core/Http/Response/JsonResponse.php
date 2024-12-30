<?php
namespace Core\Http\Response;

class JsonResponse {
    private $data;
    private $statusCode;

    private function __construct($data, $statusCode) {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->send();
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        echo json_encode($this->data);
        exit;
    }

    public static function success($data, $statusCode = 200): JsonResponse
    {
        return new self($data, $statusCode);
    }

    public static function error($message, $statusCode = 500): JsonResponse
    {
        return new self(['error' => $message], $statusCode);
    }

    public static function redirect($url): void
    {
        http_response_code(302);
        header("Location: $url");
        exit;
    }
}
