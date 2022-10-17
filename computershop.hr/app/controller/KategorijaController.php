<?php

class KategorijaController extends AutorizacijaController
{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 'kategorije' . DIRECTORY_SEPARATOR;

    private $entitet = null;
    private $poruka = '';

    public function index()
    {
        $this->view->render($this->phtmlDir . 'index', [
            'entiteti' => Kategorija::read()
        ]);
    }

    public function novi()
    {
        $novaKategorija = Kategorija::create([
            'naziv' => '',
            'opis' => ''
        ]);
        header('location: ' . App::config('url') . 'kategorija/promjena/' . $novaKategorija);
    }

    public function promjena($sifra)
    {
        if(!isset($_POST['naziv'])){
            $e = Kategorija::readOne($sifra);
            if($e == null){
                header('location: ' . App::config('url') . 'kategorija');
            }

            $this->view->render($this->phtmlDir . 'detalji', [
                'e' => $e,
                'poruka' => 'Unesite podatke'
            ]);
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->sifra = $sifra;

        if($this->kontrola()){
            Kategorija::update((array)$this->entitet);
            header('location: ' . App::config('url') . 'kategorija');
            return;
        }

        $this->view->render($this->phtmlDir . 'detalji', [
            'e' => $this->entitet,
            'poruka' => $this->poruka
        ]);
    }

    private function kontrola()
    {
        return $this->kontrolaNaziv() && $this->kontrolaOpis();
    }

    private function kontrolaNaziv()
    {
        if(strlen($this->entitet->naziv) === 0){
            $this->poruka = 'Naziv obavezno';
            return false;
        }
        return true;
    }

    private function kontrolaOpis()
    {
        if(strlen($this->entitet->opis) === 0){
            $this->poruka = 'Opis obavezno';
            return false;
        }
        return true;
    }

    public function brisanje($sifra)
    {
        Kategorija::delete($sifra);
        header('location: ' . App::config('url') . 'kategorija');
    }
}