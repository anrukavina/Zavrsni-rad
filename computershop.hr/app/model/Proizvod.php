<?php

class Proizvod
{
    public static function brisanje($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select count(*) from stavke where proizvod=:sifra

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
        
            select a.sifra, a.naziv, a.vrsta, a.cijena, a.boja, a.tezina, b.naziv as kategorija
                from proizvod a inner join kategorija b
                on a.kategorija=b.sifra
            where a.sifra=:sifra

        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch();
    }


    // CRUD - read

    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('

            select a.sifra, a.naziv, a.vrsta, a.cijena, a.boja, a.tezina, b.naziv as kategorija, count(c.sifra) as stavke
                from proizvod a inner join kategorija b
            on a.kategorija=b.sifra
                left join stavke c
            on a.sifra=c.proizvod
            group by a.sifra, a.naziv, a.vrsta, b.naziv
            order by 1,2,3
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    // CRUD - create

    public static function create($param)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into proizvod 
            (naziv,vrsta,cijena,boja,tezina,kategorija)
            values 
            (:naziv,:vrsta,:cijena,:boja,:tezina,:kategorija)
        
        ');
        $izraz->execute([
            'naziv'=>$param['naziv'],
            'vrsta'=>$param['vrsta'],
            'cijena'=>$param['cijena'],
            'boja'=>$param['boja'],
            'tezina'=>$param['tezina'],
            'kategorija'=>$param['kategorija']
        ]);
        return $veza->lastInsertId();
    }


    // CRUD - update

    public static function update($param)
    {
        $veza = DB::getInstance();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
            update proizvod set
                naziv=:naziv,
                vrsta=:vrsta,
                cijena=:cijena,
                boja=:boja,
                tezina=:tezina,
                kategorija=:kategorija
            where sifra=:sifra

        ');
        $izraz->execute([
            'naziv'=>$param['naziv'],
            'vrsta'=>$param['vrsta'],
            'cijena'=>$param['cijena'],
            'boja'=>$param['boja'],
            'tezina'=>$param['tezina'],
            'kategorija'=>$param['kategorija'],
            'sifra'=>$param['sifra']
        ]);
        $veza->commit();
    }

    // CRUD - delete

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from proizvod where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
    }
    
    public static function search($uvjet)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select a.sifra, a.naziv, a.vrsta, a.cijena, a.boja, a.tezina, b.naziv as kategorija
                from proizvod a inner join kategorija b
                on a.kategorija=b.sifra
            where concat(a.naziv,\' \', a.cijena) like :uvjet
            and a.sifra not in (select proizvod from stavke where narudzba=:narudzba)
            order by 2,4
            limit 10

        ');
        $izraz->execute([
            'uvjet' => '%' . $uvjet . '%',
            'narudzba' => $narudzba
        ]);
        return $izraz->fetchAll();
    }
       
}