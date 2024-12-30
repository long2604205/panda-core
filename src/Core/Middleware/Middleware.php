<?php

namespace PandaCore\Core\Middleware;

interface MiddlewareInterface
{
    public function handle($request, $next);
}
