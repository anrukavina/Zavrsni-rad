<?php

class ProizvodController extends AutorizacijaController
{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 'proizvodi' . DIRECTORY_SEPARATOR;

    private $entitet=null;
    private $poruka='';

    public function index()
    {
        $proizvodi = Proizvod::read();
        $nf = new NumberFormatter("hr-HR", \NumberFormatter::DECIMAL);
        $nf->setPattern('#,##0.00');
        
        foreach($proizvodi as $p){
            $p->cijena = $nf->format($p->cijena);

            // dodavanje slike
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR
            . 'proizvodi' . DIRECTORY_SEPARATOR . $p->sifra . '.jpg')){
                $p->slika = App::config('url') . 'public/img/proizvodi/' . $p->sifra . '.jpg';
            } else{
                $p->slika = App::config('url') . 'public/img/nepoznato.jpg';
            }
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
            
            // promjena slike
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR
            . 'proizvodi' . DIRECTORY_SEPARATOR . $sifra . '.jpg')){
                $e->slika = App::config('url') . 'public/img/proizvodi/' .$sifra . '.jpg';
            } else{
                $e->slika = App::config('url') . 'public/img/nepoznato.jpg';
            }

            $this->detalji($e,$kategorije,'Unesite podatke');
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->sifra=$sifra;

        if($this->kontrolaNovi()){
            Proizvod::update((array)$this->entitet);

            //promjena slike

            if(isset($_FILES['slika'])){
                move_uploaded_file($_FILES['slika']['tmp_name'],
                BP . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR 
                . 'proizvodi' . DIRECTORY_SEPARATOR . $sifra . '.jpg');
            }


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
        return $this->kontrolaNaziv() && $this->kontrolaKategorija();
    }

    private function kontrolaNaziv()
    {
        if(strlen($this->entitet->naziv)===0){
            $this->poruka = 'Naziv obavezan';
            return false;
        }
        return true;
    }

    /* private function kontrolaVrsta()
    {
        if(strlen($this->entitet->vrsta)===0){
            $this->poruka = 'Vrsta obavezno';
            return false;
        }
        return true;
    } */

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
        $proizvod = Proizvod::readOne($sifra);
        if($proizvod==null){
            header('location: ' . App::config('url') . 'proizvod');
        }
        
        if(!isset($_POST['obrisi'])){
            $this->view->render($this->phtmlDir . 'delete', [
                'proizvod' => $proizvod,
                'brisanje' => Proizvod::brisanje($sifra),
                'poruka' => 'Detalji proizvoda za brisanje'
            ]);
            return;
        }
                
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

    public function trazi()
    {
        if(!isset($_GET['term'])){
            return;
        }
        echo json_encode(Proizvod::search($_GET['term'],$_GET['narudzba']));
    }
}