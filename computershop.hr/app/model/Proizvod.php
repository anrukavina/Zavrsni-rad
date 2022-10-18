<?php

class Proizvod
{
    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select a.naziv, a.vrsta, a. cijena, a.boja, a.tezina, b.naziv as kategorija
                from proizvod a inner join kategorija b
                on a.kategorija=b.sifra
            where a.sifra=:sifra  

        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetchAll();
    }

    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('

            select a.naziv, a.vrsta, a. cijena, a.boja, a.tezina, b.naziv as kategorija
                from proizvod a inner join kategorija b
            on a.kategorija=b.sifra
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function create($param)
    {
        $veza = DB::getInstance();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
            insert into proizvod 
            (naziv,vrsta,cijena,boja,tezina,kategorija)
            values 
            (:naziv,:vrsta,:cijena,:boja,:tezina,:kategorija);
        
        ');
        $izraz->execute([
            'naziv'=>$param['naziv'],
            'vrsta'=>$param['vrsta'],
            'cijena'=>$param['cijena'],
            'boja'=>$param['boja'],
            'tezina'=>$param['tezina'],
            'kategorija'=>$sifraKategorija
        ]);
        $sifraProizvod = $veza->lastInsertId();
        $veza->commit();
        return $sifraProizvod;
    }

    public static function update($param)
    {
        $veza = DB::getInstance();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
            select kategorija from proizvod where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$param['sifra']
        ]);
        $sifraKategorija = $izraz->fetchColumn();

        $izraz = $veza->prepare('
        
            update kategorija set
                naziv=:naziv,
                opis=:opis
            where sifra=:sifra

        ');
        $izraz->execute([
            'naziv'=>$param['naziv'],
            'opis'=>$param['opis'],
            'sifra'=>$sifraKategorija
        ]);

        $izraz = $veza->prepare('
        
            update proizvod set
                naziv=:naziv,
                vrsta=:vrsta,
                cijena=:cijena,
                boja=:boja,
                tezina=:tezina
            where sifra=:sifra
        
        ');
        $izraz->execute([
            'naziv'=>$param['naziv'],
            'vrsta'=>$param['vrsta'],
            'cijena'=>$param['cijena'],
            'boja'=>$param['boja'],
            'tezina'=>$param['tezina'],
            'sifra'=>$param['sifra']
        ]);

        $veza->commit();
    }
}