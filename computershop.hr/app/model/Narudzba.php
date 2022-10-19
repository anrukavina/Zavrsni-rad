<?php

class Narudzba
{
    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select a.sifra, a.broj_pracenja, a.datum_narudzbe, a.datum_isporuke, b.ime as korisnik, b.prezime
                from narudzba a inner join korisnik b
                on a.korisnik = b.sifra
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
        
            select a.sifra, a.broj_pracenja, a.datum_narudzbe, a.datum_isporuke, b.ime as korisnik, b.prezime
                from narudzba a inner join korisnik b
                on a.korisnik = b.sifra
            order by a.sifra
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    // CRUD - create

    public static function create($param)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into narudzba
                (broj_pracenja, datum_narudzbe, datum_isporuke, korisnik)
            values 
                (:broj_pracenja, :datum_narudzbe, :datum_isporuke, :korisnik)
        
        ');
        $izraz->execute([
            'broj_pracenja'=>$param['broj_pracenja'],
            'datum_narudzbe'=>$param['datum_narudzbe'],
            'datum_isporuke'=>$param['datum_isporuke'],
            'korisnik'=>$param['korisnik']
        ]);
        return $veza->lastInsertId();
    }

    // CRUD - update

    public static function update($param)
    {
        $veza = DB::getInstance();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
            update narudzba set
                broj_pracenja=:broj_pracenja,
                datum_narudzbe=:datum_narudzbe,
                datum_isporuke=:datum_isporuke,
                korisnik=:korisnik
            where sifra=:sifra
        
        ');
        $izraz->execute([
            'broj_pracenja'=>$param['broj_pracenja'],
            'datum_narudzbe'=>$param['datum_narudzbe'],
            'datum_isporuke'=>$param['datum_isporuke'],
            'korisnik'=>$param['korisnik'],
            'sifra'=>$param['sifra']
        ]);
        $veza->commit();
    }

    // CRUD - delete

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from narudzba where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
    }
}