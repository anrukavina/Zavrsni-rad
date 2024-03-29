<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// echo __DIR__;  Rezultat: C:\Users\antun\OneDrive\Dokumenti\GitHub\Zavrsni-rad\mojapp.hr 

define ('BP',__DIR__ . DIRECTORY_SEPARATOR);
define('BP_APP', BP . 'app' . DIRECTORY_SEPARATOR);

$zaAutoLoad = [
    BP_APP . 'core',
    BP_APP . 'controller',
    BP_APP . 'model'
];

$putanje = implode(PATH_SEPARATOR,$zaAutoLoad);

set_include_path($putanje);

// Autoload register koristimo za automatsko učitavanje klasa
// kako svaku klasu zasebno ne bi morali učitavati preko require funkcije (pr. 'require 'KorisnikController.php';)

spl_autoload_register(function($klasa){
    $putanje = explode(PATH_SEPARATOR,get_include_path());
    foreach($putanje as $p) {
        $datoteka = $p . DIRECTORY_SEPARATOR . $klasa . '.php';
        if(file_exists($datoteka)){
            require_once $datoteka;
            break;
        }
    }
});

App::start();