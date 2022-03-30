<?php
namespace App\Services;

class Prix{
    public function sendPrix($departs,$arrive)
    {
        $prix = 0;
        if ($departs == $arrive ) {
            $prix=1000;
        } elseif (($departs !== 'BASSAM' && $arrive == 'BASSAM') OR ($departs == 'BASSAM' && $arrive !== 'BASSAM')) {
            $prix=2000;
        }
        elseif (($departs !== 'ANYAMA' && $arrive == 'ANYAMA') OR ($departs == 'ANYAMA' && $arrive !== 'ANYAMA')) {
            $prix=2000;
        }
        else {
            $prix=1500;
            
        }
        return $prix;
    }
    
}
