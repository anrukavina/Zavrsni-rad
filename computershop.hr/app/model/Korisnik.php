<?php

class Korisnik 
{
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
        
            select * from korisnik

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