<?php

class LoginController extends Controller
{
    public function prijava()
    {
       /*  $this->view->render('prijava',[
            'poruka'=>'Popunite tražene podatke'
        ]); */
        $this->prijavaView('operater@computershop.hr','Popunite tražene podatke');
    }

    public function autorizacija()
    {
        if(!isset($_POST['email']) || !isset($_POST['password'])){
            /* $this->view->render('prijava',[
                'poruka'=>'Email i lozinka obavezno'
            ]); */
            $this->prijava();
            return;
        }

        if(strlen(trim($_POST['email']))===0){
           /*  $this->view->render('prijava',[
                'poruka'=>'Email obavezno'
            ]); */
            $this->prijavaView('','Email obavezno');
            return;
        }

        if(strlen(trim($_POST['password']))===0){
             $this->prijavaView($_POST['email'],'Lozinka obavezno');
             return;
        }

        // Ovdje je sigurno da su postavljeni email i lozinka
        $operater = Operater::autoriziraj($_POST['email'],$_POST['password']);
        if($operater==null){
            $this->prijavaView($_POST['email'],'Email i/ili lozinka neispravni');
             return;
        }    


        // Ovdje sam siguran da si i autoriziran
        $_SESSION['autoriziran'] = $operater;
        //$nadzornaPloca = new NadzornaplocaController();
        //$nadzornaPloca->index();
        // Ovo radimo umjesto instance klase nadzorne ploce kao gore, da nam u url-u ne bi prikazivalo autorizacija, nego nadzorna ploca
        header('location:' . App::config('url') . 'nadzornaploca');
    }

    private function prijavaView($email,$poruka)
    {
        $this->view->render('prijava',[
            'poruka'=>$poruka,
            'email'=>$email
        ]);
    }

    public function odjava()
    {
        unset($_SESSION['autoriziran']);
        session_destroy();
        $this->prijavaView('','Uspješno ste odjavljeni');
    }
}