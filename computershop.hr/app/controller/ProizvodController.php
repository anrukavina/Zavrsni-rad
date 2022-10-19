<?php

class ProizvodController extends AutorizacijaController
{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 'proizvodi' . DIRECTORY_SEPARATOR;

    private $entitet=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->phtmlDir . 'index', [
            'entiteti'=>Proizvod::read()
        ]);
    }

    public function novi()
    {
        $noviProizvod = Proizvod::create([
            'naziv'=>'',
            'vrsta'=>'',
            'cijena'=>'',
            'boja'=>'',
            'tezina'=>'',
            'kategorija'=>1
        ]);
        header('location: ' . App::config('url') . 'proizvod/promjena/' . $noviProizvod);
    }

    public function promjena($sifra)
    {
        $kategorije = $this->ucitajKategorije();
        if(!isset($_POST['naziv'])){

            $e = Proizvod::readOne($sifra);
            if($e==null){
                header('location: ' . App::config('url') . 'proizvod');
            }
            
            /* $this->view->render($this->phtmlDir . 'detalji',[
                'e'=>$e,
                'poruka'=>'Unesite podatke'
            ]); */

            $this->detalji($e,$kategorije,'Unesite podatke');
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->sifra=$sifra;

        if($this->kontrolaNovi()){
            Proizvod::update((array)$this->entitet);
            header('location: ' . App::config('url') . 'proizvod');
            return;
        }

       /*  $this->view->render($this->phtmlDir . 'detalji', [
            'e'=>$this->entitet,
            'poruka'=>$this->poruka
        ]); */

        $this->detalji($this->entitet,$kategorije,$this->poruka);
    }

    private function detalji($e,$kategorije,$poruka)
    {
        $this->view->render($this->phtmlDir . 'detalji', [
            'e'=>$e,
            'kategorije'=>$kategorije,
            'poruka'=>$poruka
        ]);
    }

    private function kontrolaNovi()
    {
        return $this->kontrolaNaziv() && $this->kontrolaVrsta() && $this->kontrolaKategorija();
    }

    private function kontrolaNaziv()
    {
        if(strlen($this->entitet->naziv)===0){
            $this->poruka = 'Naziv obavezan';
            return false;
        }
        return true;
    }

    private function kontrolaVrsta()
    {
        if(strlen($this->entitet->vrsta)===0){
            $this->poruka = 'Vrsta obavezno';
            return false;
        }
        return true;
    }

    private function kontrolaKategorija()
    {
        if($this->entitet->kategorija==0){
            $this->poruka='Obavezno kategorija';
            return false;
        }
        return true;
    }

    public function brisanje($sifra)
    {
        Proizvod::delete($sifra);
        header('location: ' . App::config('url') . 'proizvod');
    }

    private function ucitajKategorije()
    {
        $kategorije = [];
        $k = new stdClass();
        $k->sifra=0;
        $k->naziv='Odaberi kategoriju';
        $kategorije[]=$k;
        foreach(Kategorija::read() as $kategorija){
            $kategorije[]=$kategorija;
        }
        return $kategorije;
    }

    /* public function index()
    {
        $nf = new NumberFormatter("hr-HR", \NumberFormatter::DECIMAL);
        $nf->setPattern('#,##0.00');
        $proizvodi = Proizvod::read();
        foreach($proizvodi as $p){
            $p->cijena = $nf->format($p->cijena);
            $p->tezina = $nf->format($p->tezina);
        }


        $this->view->render($this->phtmlDir . 'index', [
            'entiteti' => $proizvodi    
        ]);
    }

    public function novi()
    {
        $noviProizvod = Proizvod::create([
            'naziv'=>'',
            'vrsta'=>'',
            'cijena'=>'',
            'boja'=>'',
            'tezina'=>'',
            'kategorija'=>1
        ]);
        header('location:' . App::config('url') . 'proizvod/promjena/' . $noviProizvod);
    }

    public function promjena($sifra)
    {
        $kategorije = $this->ucitajKategorije();

        if(!isset($_POST['naziv'])){

            $e = Proizvod::readOne($sifra);
            if($e = null){
                header('location: ' . App::config('url') . 'proizvod');
            }

            $this->detalji($e,$kategorije, 'Unesite podatke');
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->sifra=$sifra;

        if($this->kontrola()){
            Proizvod::update((array)$this->entitet);
            header('location: ' . App::config('url') . 'proizvod');
            return;
        }

        $this->detalji($this->entitet,$kategorije,$this->poruka);
    }

    public function brisanje($sifra)
    {
        Proizvod::delete($sifra);
        header('location: ' . App::config('url') . 'proizvod');
    }

    private function detalji($e,$kategorije,$poruka)
    {
        $this->view->render($this->phtmlDir . 'detalji', [
            'e'=>$e,
            'kategorije'=>$kategorije,
            'poruka'=>'poruka'
        ]);
    }

    private function ucitajKategorije()
    {
        $kategorije = [];
        $s = new stdClass();
        $s->sifra=0;
        $s->naziv='Odaberi kategoriju';
        $kategorije[]=$s;
        foreach(Kategorija::read() as $kategorija){
            $kategorije[]=$kategorija;
        }
        return $kategorije;
    }

    private function kontrola()
    {
        return $this->kontrolaNaziv() && $this->kontrolaVrsta();
    }

    private function kontrolaNaziv()
    {
        if(strlen($this->entitet->naziv)===0){
            $this->poruka = 'Naziv obavezno';
            return false;
        }
        return true;
    }

    private function kontrolaVrsta()
    {
        if(strlen($this->entitet->vrsta)===0){
            $this->poruka = 'Vrsta obavezno';
            return false;
        }
        return true;
    } */

}