<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MACGeneratorController extends Controller
{
    private $macaddress = '00:00:00:00:00:00';
    private $characterset='0123456789ABCDEF';
    public function generateMAC() {
        $this->macaddress=$this->randomBracket().":".$this->randomBracket().":".$this->randomBracket().":".$this->randomBracket().":".$this->randomBracket().":".$this->randomBracket();
        return $this->getCurrentMAC();
    }
    private function randomBracket() {
        return $this->characterset[rand(0,strlen($this->characterset)-1)]."".$this->characterset[rand(0,strlen($this->characterset)-1)];
    }
    public function getCurrentMAC() {
        return $this->macaddress;
    }

    public function index(){
        $lenght = $_GET['number'] ?? 1;
        $generator = new MACGeneratorController();
        echo '<code style="color: red">'. route('mac.index').'?number=xxx' .'</code><br><br>' ;

        for ($i=0;$i<$lenght;$i++)
            echo $generator->generateMAC()."<br>";
        unset($generator);
    }

}
