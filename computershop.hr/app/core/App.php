<?php

class App
{
    public static function start()
    {
        $ruta = Request::getRuta(); // $ruta = /korisnik/index npr.

        $dijelovi = explode('/', $ruta);

        // Log::log($dijelovi);

        $klasa = '';
        if(!isset($dijelovi[1]) || $dijelovi[1] === ''){
            $klasa = 'IndexController';
        } else {
            $klasa = ucfirst($dijelovi[1]) . 'Controller';
        }
        // Log::log($klasa);

        $metoda = '';
        if(!isset($dijelovi[2]) || $dijelovi[2] === ''){
            $metoda = 'index';
        } else {
            $metoda = $dijelovi[2];
        }

        // Log::log($metoda);

        if(class_exists($klasa) && method_exists($klasa,$metoda)){
            $instanca = new $klasa();
            $instanca->$metoda();
        } else {
            echo 'Ne postoji ' . $klasa . '-&gt;' . $metoda;
        }
    }

    public static function config($kljuc)
    {
        $configFile = BP_APP . 'konfiguracija.php';
        $config = require $configFile;

        if(isset($config[$kljuc])){
            return $config[$kljuc];
        } else{
            return 'Ključ ' . $kljuc . ' ne postoji u datoteci ' . $configFile; 
        }
    }
}