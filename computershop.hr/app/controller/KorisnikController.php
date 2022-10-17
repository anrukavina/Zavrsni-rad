<?php

class KorisnikController extends AutorizacijaController 
{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 'korisnici' . DIRECTORY_SEPARATOR;

    private $korisnik = null;
    private $poruka = '';
    
    public function index()
    {
        $korisnici = Korisnik::read();

        $this->view->render($this->phtmlDir . 'read', [
            'korisnici' => $korisnici
        ]);
    }

    // Promjena korisnika

    public function promjena($sifra)
    {
        if(!isset($_POST['ime'])){
        $this->view->render($this->phtmlDir . 'update', [
            'korisnik' => Korisnik::readOne($sifra),    //$sifra se mijenja u ovo jer smo napravili readOne
            'poruka' => 'Promijenite podatke'
        ]);
        return;
        }

        $this->korisnik = (object) $_POST;
        $this->korisnik->sifra = $sifra;

        if($this->kontrolaPromjena()){
            Korisnik::update((array)$this->korisnik);
            header('location: ' . App::config('url') . 'korisnik');
            return;
        }

        $this->view->render($this->$phtmlDir . 'update', [
            'korisnik' => $this->korisnik,
            'poruka' => $this->poruka
        ]);
    }


    // Brisanje korisnika

    public function brisanje($sifra)
    {
        Korisnik::delete($sifra);
        header('location: ' . App::config('url') . 'korisnik');
    }


    // Dodavanje novog korisnika

    public function novi()
    {
        if(!isset($_POST['ime'])){
            $this->pripremiKorisnik();
            $this->view->render($this->phtmlDir . 'create', [
                'korisnik'=>$this->korisnik,
                'poruka'=>'Popunite sve podatke'
            ]);
            return;
        }
        $this->korisnik = (object) $_POST;
        
        if($this->kontrolaNovi()){
            Korisnik::create((array)$this->korisnik);
            header('location: ' . App::config('url') . 'korisnik');
            return;
        } 

        $this->view->render($this->phtmlDir . 'create', [
            'korisnik'=>$this->korisnik,
            'poruka'=>$this->poruka
        ]);
    }

    // Kontrola novog

    private function kontrolaNovi()
    {
        return $this->kontrolaIme() && $this->kontrolaPrezime();
    }

    private function kontrolaPromjena()
    {
        return $this->kontrolaIme();
    }

    private function kontrolaIme()
    {
        if(strlen($this->korisnik->ime)===0){
            $this->poruka = 'Ime obavezno';
            return false;
        }
        return true;
    }

    private function kontrolaPrezime()
    {
        if(strlen($this->korisnik->prezime)===0){
            $this->poruka = 'Prezime obavezno';
            return false;
        }
        return true;
    }

    private function pripremiKorisnik()
    {
        $this->korisnik=new stdClass();
            $this->korisnik->ime='';
            $this->korisnik->prezime='';
            $this->korisnik->oib='';
            $this->korisnik->drzava='';
            $this->korisnik->grad='';
            $this->korisnik->postanski_broj='';
            $this->korisnik->ulica='';
            $this->korisnik->kucni_broj='';
            $this->korisnik->email='';
            $this->korisnik->datum_rodenja='';
            $this->korisnik->broj_telefona='';
            $this->korisnik->spol='';
    }
}