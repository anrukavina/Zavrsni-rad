<?php

class Operater
{
    public static function autoriziraj($email,$lozinka)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('

            select * from operater where email=:email;

        ');

        // :email dvotočka ide da bi se spriječio SQL injection

        $izraz->execute([
            'email'=>$email
        ]);
        $operater = $izraz->fetch();
        if($operater==null){
            return null;
        }
        if(!password_verify($lozinka,$operater->lozinka)){
            return null;
        }
        unset($operater->lozinka); // da bi se spriječilo spremanje lozinke u session
        return $operater;
    }
}