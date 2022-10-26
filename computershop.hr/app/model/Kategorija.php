<?php

class Kategorija 
{
    public static function brisanje($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select count(*) from proizvod where kategorija=:sifra

        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
        $ukupno = $izraz->fetchColumn();
        return $ukupno==0;
    }

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
        
            select a.sifra, a.naziv, a.opis, count(b.sifra) as proizvod from
                kategorija a left join proizvod b
                on a.sifra = b.kategorija 
            group by a.sifra, a.naziv, a.opis 
            order by 1,2
       
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