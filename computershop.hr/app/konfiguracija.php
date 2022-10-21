<?php


$dev = $_SERVER['SERVER_ADDR'] == '127.0.0.1';

if($dev){
    return [
        'dev'=>$dev,
        'url'=>'http://computershop.hr/',
        'nazivApp'=>'DEV Computer Shop',
        'baza'=>[
            'server'=>'localhost',
            'baza'=>'online_trgovina',
            'korisnik'=>'antun',
            'lozinka'=>'antun'
        ]
    ];
} else{
    return [
        'dev'=>$dev,
        'url'=>'https://polaznik08.edunova.hr/',
        'nazivApp'=>'Computer Shop',
        'baza'=>[
            'server'=>'localhost',
            'baza'=>'helios_onlinetrgovina',
            'korisnik'=>'helios_admin',
            'lozinka'=>'gyptmH-u?HRC' 
        ]
    ];
}

