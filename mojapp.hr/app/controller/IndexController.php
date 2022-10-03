<?php

class IndexController
{
    public function index()
    {
        //echo 'IndexController->index';
        $view = new View();
        $view->render('index');
    }
}