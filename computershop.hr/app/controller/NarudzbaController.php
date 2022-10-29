<?php

class NarudzbaController extends AutorizacijaController
{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 'narudzbe' . DIRECTORY_SEPARATOR;

    private $entitet;
    private $poruka = '';

    public function index()
    {
        $narudzbe = Narudzba::read();
        foreach($narudzbe as $n){
            if($n->datum_narudzbe!=null && $n->datum_narudzbe!='0000-00-00 00:00:00'
            || $n->datum_isporuke!=null && $n->datum_isporuke!='0000-00-00 00:00:00'){
                $n->datum_narudzbe = date('d.m.Y.', strtotime($n->datum_narudzbe));
                $n->datum_isporuke = date('d.m.Y.', strtotime($n->datum_isporuke));
            } else{
                $n->datum_narudzbe='Nije postavljeno';
                $n->datum_isporuke='Nije postavljeno';
            }
        }

        $this->view->render($this->phtmlDir . 'index', [
            'entiteti' => $narudzbe
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

            if($e->datum_narudzbe!=null){
                $e->datum_narudzbe = date('Y-m-d', strtotime($e->datum_narudzbe));
            }else{
                $e->datum_narudzbe= '';
            }

            if($e->datum_isporuke!=null){
                $e->datum_isporuke = date('Y-m-d', strtotime($e->datum_isporuke));
            }else{
                $e->datum_isporuke= '';
            }

            if($e==null){
                header('location: ' . App::config('url') . 'narudzba');
            }

            $this->detalji($e,$korisnici,'Unesite podatke');
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->sifra = $sifra;

        if($this->kontrolaNova()){
            if($this->entitet->korisnik==0){
                $this->entitet->korisnik=null;
            }

            if($this->entitet->datum_narudzbe==''){
                $this->entitet->datum_narudzbe=null;
            }

            Narudzba::update((array)$this->entitet);
            header('location:' . App::config('url') . 'narudzba');
            return;
        }

        $this->detalji($this->entitet,$korisnici,$this->poruka);
    }

    private function detalji($e,$korisnici,$poruka)
    {
        $this->view->render($this->phtmlDir . 'detalji', [
            'e' => $e,
            'korisnici' => $korisnici,
            'poruka' => $poruka,
            'css' => '<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">',
            'js' => '<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
            <script>
                let url=\'' .  App::config('url') .  '\';
                let narudzba=' . $e->sifra . ';    
            </script>
            <script src="'. App::config('url') . 'public/js/detaljiNarudzbe.js"></script>'
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
        $narudzba = Narudzba::readOne($sifra);
        if($narudzba==null){
            header('location: ' . App::config('url') . 'narudzba');
        }
        
        if(!isset($_POST['obrisi'])){
            $this->view->render($this->phtmlDir . 'delete', [
                'narudzba' => $narudzba,
                'brisanje' => Narudzba::brisanje($sifra),
                'poruka' => 'Detalji narudžbe za brisanje'
            ]);
            return;
        }
                
        Narudzba::delete($sifra);
        header('location: ' . App::config('url') . 'narudzba');
    }

    public function dodajproizvod()
    {
        if(!isset($_GET['narudzba']) || !isset($_GET['proizvod'])){
            return;
        }
        Narudzba::dodajproizvod($_GET['narudzba'],$_GET['proizvod'],$_GET['kolicina']);
    }
    
    public function obrisiproizvod()
    {
        if(!isset($_GET['narudzba']) || !isset($_GET['proizvod'])){
            return;
        }
        Narudzba::obrisiproizvod($_GET['narudzba'],$_GET['proizvod']);
    }
}