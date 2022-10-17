<?php

class Kategorija 
{
    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('

            select * from kategorija where sifra=:sifra
        
        ');

        $izraz->execute([
            'sifra' => $sifra
        ]);
        return $izraz->fetch();
    }

    // CRUD - read

    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from kategorija
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    // CRUD - create

    public static function create($kategorija)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('

            insert into 
            kategorija (naziv,opis)
            values (:naziv,:opis)
        
        ');
        $izraz->execute($kategorija);
        return $veza->lastInsertId();
    }

    // CRUD - update

    public static function update($kategorija)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update kategorija set 
                naziv=:naziv,
                opis=:opis
            where sifra=:sifra
        ');
        $izraz->execute($kategorija);
    }

    // CRUD - delete

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from kategorija where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
    }
}