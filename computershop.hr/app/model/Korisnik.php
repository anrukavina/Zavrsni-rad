<?php

class Korisnik 
{
    public static function brisanje($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('

            select count(*) from narudzba where korisnik=:sifra

        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        $ukupno = $izraz->fetchColumn();
        return $ukupno==0;
    }


    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from korisnik where sifra=:sifra

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
        
            select a.sifra,a.ime,a.prezime,a.oib,a.drzava,a.grad,a.postanski_broj,a.ulica,a.kucni_broj,a.email,
            a.datum_rodenja,a.broj_telefona,a.spol, count(b.sifra) as narudzba from
            korisnik a left join narudzba b
            on a.sifra = b.korisnik
            group by a.sifra,a.ime,a.prezime,a.oib,a.drzava,a.grad,a.postanski_broj,a.ulica,a.kucni_broj,a.email,
            a.datum_rodenja,a.broj_telefona, a.spol
            order by 1,3,2

        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    // CRUD - create
    public static function create($korisnik)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into
            korisnik(sifra,ime,prezime,oib,drzava,grad,postanski_broj,ulica,kucni_broj,email,datum_rodenja,broj_telefona,spol)
            values (null,:ime,:prezime,:oib,:drzava,:grad,:postanski_broj,:ulica,:kucni_broj,:email,:datum_rodenja,:broj_telefona,:spol);
        
        ');
        $izraz->execute($korisnik);
    }

    // CRUD - update
    public static function update($korisnik)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update korisnik set
                ime=:ime,
                prezime=:prezime,
                oib=:oib,
                drzava=:drzava,
                grad=:grad,
                postanski_broj=:postanski_broj,
                ulica=:ulica,
                kucni_broj=:kucni_broj,
                email=:email,
                datum_rodenja=:datum_rodenja,
                broj_telefona=:broj_telefona,
                spol=:spol
            where sifra=:sifra
        
        ');
        $izraz->execute($korisnik);
    }

    // CRUD - delete
    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from korisnik where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra' => $sifra
        ]);       
    }
}