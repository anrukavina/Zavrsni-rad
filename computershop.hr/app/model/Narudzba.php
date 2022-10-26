<?php

class Narudzba
{
    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from narudzba where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
        $narudzba = $izraz->fetch();
        if($narudzba->datum_narudzbe=='0000-00-00 00:00:00'){
            $narudzba->datum_narudzbe=null;
        }
        $izraz = $veza->prepare('
        
            select a.sifra, a.naziv, c.kolicina
                from proizvod a inner join kategorija b
                on a.kategorija=b.sifra 
            inner join stavke c
                on c.proizvod=a.sifra 
            where c.narudzba=:sifra 
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);
        $narudzba->proizvodi = $izraz->fetchAll();
        return $narudzba;
    }

    // CRUD - read

    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select a.sifra, a.broj_pracenja, a.datum_narudzbe, a.datum_isporuke, concat (b.ime,\' \',b.prezime) as korisnik, count(c.proizvod) as proizvoda
                from narudzba a inner join korisnik b
                on a.korisnik = b.sifra
                left join stavke c
                on a.sifra = c.narudzba
            group by a.sifra, concat (b.ime,\' \',b.prezime), a.broj_pracenja, a.datum_narudzbe, a.datum_isporuke
        
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

    // Dodavanje proizvoda

    public static function dodajproizvod($narudzba,$proizvod,$kolicina)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into stavke (narudzba,proizvod,kolicina) 
            values (:narudzba,:proizvod,:kolicina)

        ');
        $izraz->execute([
            'narudzba' => $narudzba,
            'proizvod' => $proizvod,
            'kolicina' => $kolicina
        ]);
    }

    // Brisanje proizvoda

    public static function obrisiproizvod($narudzba,$proizvod)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from stavke where narudzba=:narudzba and proizvod=:proizvod

        ');
        $izraz->execute([
            'narudzba' => $narudzba,
            'proizvod' => $proizvod
        ]);
    }
}