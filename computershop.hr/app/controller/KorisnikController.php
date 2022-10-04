<?php

class KorisnikController extends Controller 
{
    private $putanja = 'privatno' . DIRECTORY_SEPARATOR . 'korisnik' . DIRECTORY_SEPARATOR;
    
    public function index()
    {
        $this->view->render($this->putanja . 'index', [
            'grad'=>'Požega',
            'brojevi'=>[
                1,4,7,8
            ]
        ]);
    }
}