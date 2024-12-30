<?php
namespace PandaCore\Core\Controllers;

class Controller
{
    public function view($view, $data = []): void
    {
        extract($data);
        include_once "../views/{$view}.php";
    }
}