<?php
class Room{
	public $ip1;
	public $ip2;
	public $giocatore1;
	public $giocatore2;
	public $matrice_gioco= Array(0=> Array(-1,-1,-1), 1 => Array(-1,-1,-1),2 =>Array (-1,-1,-1) );
	public $id_partita;
	public function stampa_matrice(){
		echo $this->matrice_gioco[1][2];
	}
	public function __construct($ip1, $ip2,$id_partita,$giocatore1,$giocatore2) {
                $this->$ip1 = $ip1;
                $this->$ip2 = $ip2;
                $this->$id_partita = $id_partita;
                $this->$giocatore1=$giocatore1;
                $this->$giocatore2=$giocatore2;

        }
    


}
?>
