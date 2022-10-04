<?php

class View
{
    private $predlozak;

    public function __construct($predlozak='predlozak')
    {
        $this->predlozak=$predlozak;
    }

    public function render($phtmlStranica,$parametri=[])
    {
        // Log::log($parametri); *** Na ovaj način provjeravam parametre koje je Controller poslao View-u ***
        $viewDatoteka = BP_APP . 'view' . 
                    DIRECTORY_SEPARATOR . $phtmlStranica . '.phtml';
        if(file_exists($viewDatoteka)){
            ob_start();
            extract($parametri);
            include_once $viewDatoteka;
            $sadrzaj = ob_get_clean();
        } else{
            $sadrzaj = 'View datoteka ne postoji: ' . $viewDatoteka;
        }

        include_once BP_APP . 'view' . 
                    DIRECTORY_SEPARATOR . $this->predlozak . '.phtml';
    }
}