<?php

namespace PandaCore\Core\Http\Request;

class Request
{
    /**
     * Retrieve all data from GET, POST, and REQUEST
     *
     * @return array
     */
    public function all(): array
    {
        return array_merge(
            $this->get(),
            $this->post(),
            $this->request()
        );
    }

    /**
     * Retrieve data from $_GET (query parameters)
     *
     * @param string|null $key
     * @return mixed
     */
    public function get(string $key = null): mixed
    {
        if ($key) {
            return $_GET[$key] ?? null;
        }
        return $_GET;
    }

    /**
     * Retrieve data from $_POST (body of the request)
     *
     * @param string|null $key
     * @return mixed
     */
    public function post(string $key = null): mixed
    {
        if ($key) {
            return $_POST[$key] ?? null;
        }
        return $_POST;
    }

    /**
     * Retrieve data from $_REQUEST (includes GET and POST)
     *
     * @param string|null $key
     * @return mixed
     */
    public function request(string $key = null): mixed
    {
        if ($key) {
            return $_REQUEST[$key] ?? null;
        }
        return $_REQUEST;
    }

    /**
     * Retrieve data from $_FILES (file data)
     *
     * @param string|null $key
     * @return mixed
     */
    public function file(string $key = null): mixed
    {
        if ($key) {
            return $_FILES[$key] ?? null;
        }
        return $_FILES;
    }

    /**
     * Retrieve data from $_SERVER (server environment data, including method, headers, etc.)
     *
     * @param string|null $key
     * @return mixed
     */
    public function server(string $key = null): mixed
    {
        if ($key) {
            return $_SERVER[$key] ?? null;
        }
        return $_SERVER;
    }

    /**
     * Retrieve the HTTP method of the request (GET, POST, PUT, DELETE).
     * *
     * @return string
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Check if the request is an AJAX
     *
     * @return bool
     */
    public function ajax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Check if the request is an AJAX request.
     * *
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    /**
     * Check if the request is POST.
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * Check if the request is PUT
     *
     * @return bool
     */
    public function isPut(): bool
    {
        return $this->method() === 'PUT';
    }

    /**
     * Check if the request is DELETE
     *
     * @return bool
     */
    public function isDelete(): bool
    {
        return $this->method() === 'DELETE';
    }

    /**
     * Get the value of an HTTP header
     *
     * @param string|null $key
     * @return mixed
     */
    public function header(string $key = null): mixed
    {
        $headers = getallheaders();

        if ($key) {
            return $headers[$key] ?? null;
        }

        return $headers;
    }
}
