<?php

class NarudzbaController extends AutorizacijaController
{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 'narudzbe' . DIRECTORY_SEPARATOR;

    private $entitet = null;
    private $poruka = '';

    public function index()
    {
        $this->view->render($this->phtmlDir . 'index', [
            'entiteti'=>Narudzba::read()
        ]);
    }

    public function nova()
    {
        $novaNarudzba = Narudzba::create([
            'broj_pracenja'=>'',
            'datum_narudzbe'=>'',
            'datum_isporuke'=>'',
            'korisnik'=>1
        ]);
        header('location: ' . App::config('url') . 'narudzba/promjena/' . $novaNarudzba);
    }

    public function promjena($sifra)
    {
        $korisnici = $this->ucitajKorisnike();

        if(!isset($_POST['broj_pracenja'])){
            $e = Narudzba::readOne($sifra);
            if($e==null){
                header('location: ' . App::config('url') . 'narudzba');
            }

            $this->detalji($e,$korisnici,'Unesite podatke');
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->sifra = $sifra;

        if($this->kontrolaNova()){
            Narudzba::update((array)$this->entitet);
            header('location:' . App::config('url') . 'narudzba');
            return;
        }

        $this->detalji($this->entitet,$korisnici,$this->poruka);
    }

    private function detalji($e,$korisnici,$poruka)
    {
        $this->view->render($this->phtmlDir . 'detalji', [
            'e'=>$e,
            'korisnici'=>$korisnici,
            'poruka'=>$poruka
        ]);
    }

    private function kontrolaNova()
    {
        return $this->kontrolaBrojPracenja() && $this->kontrolaDatumNarudzbe() && $this->kontrolaDatumIsporuke()
        && $this->kontrolaKorisnik();
    }

    private function kontrolaBrojPracenja()
    {
        if(strlen($this->entitet->broj_pracenja)===0){
            $this->poruka = 'Broj praćenja obavezan';
            return false;
        }
        return true;
    }

    private function kontrolaDatumNarudzbe()
    {
        if(strlen($this->entitet->datum_narudzbe)===0){
            $this->poruka = 'Datum narudžbe obavezan';
            return false;
        }
        return true;
    }

    private function kontrolaDatumIsporuke()
    {
        if(strlen($this->entitet->datum_isporuke)===0){
            $this->poruka = 'Datum isporuke obavezan';
            return false;
        }
        return true;
    }

    private function kontrolaKorisnik()
    {
        if(strlen($this->entitet->korisnik)==0){
            $this->poruka = 'Obavezno korisnik';
            return false;
        }
        return true;
    }

    private function ucitajKorisnike()
    {
        $korisnici = [];
        $k = new stdClass();
        $k->sifra = 0;
        $k->ime = 'Odaberi';
        $k->prezime = 'korisnika';
        $korisnici[] = $k;
        foreach(Korisnik::read() as $korisnik){
            $korisnici[]=$korisnik;
        }
        return $korisnici;
    }

    public function brisanje($sifra)
    {
        Narudzba::delete($sifra);
        header('location: ' . App::config('url') . 'narudzba');
    }
    
}