<?php
	$servername = "192.168.1.2";
	$username = "alessio";
	$password = "milan96";
	$dbname = "trismultiplayer";
	$operazione=$_GET["id"];
	$giocatore=$_GET["username"];
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			  $ip=$_SERVER['HTTP_CLIENT_IP'];
			//Is it a proxy address
			}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}else{
			  $ip=$_SERVER['REMOTE_ADDR'];
			}
			if($ip=="::1"){
				$ip="127.0.0.1";
			}
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	


	//ritorna elenco utenti online
	if($operazione==1){
		$sql="SELECT Username,Nome,Cognome,IndirizzoIP
			FROM Utenti
			WHERE Online=1
			AND Username<>'$giocatore'";
		$result=$conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo $row["Username"]. ";" . $row["Nome"]. ";" . $row["Cognome"]. ";".$row["IndirizzoIP"].";";


	    }
	} else {
	    echo "0";
	}
	$conn->close();


	}


	//setta  l' utente offline con quel indirizzo

	else if($operazione==2){
		
		$IndirizzoIP=ip2long($ip);
		

		$sql="UPDATE Utenti
		SET Online=0
		WHERE Username='$giocatore'";
		if ($conn->query($sql) === TRUE) {
	    echo "Success";
	} else {
	    echo "Error updating record: " . $conn->error;
	}

	$conn->close();


	}


	//setta  l' utente online con quel indirizzo
	else if($operazione==3){
		//Test if it is a shared client
		$flag=0;
			
		
		$IndirizzoIP=ip2long($ip);
		

		$sql="UPDATE Utenti
		SET Online=1
		WHERE Username='$giocatore'";
		if ($conn->query($sql) === TRUE) {
	    	$flag=$flag+1;
	} else {
	    echo "Error updating record online: " . $conn->error;
	}
	$sql="UPDATE Utenti
		SET IndirizzoIP=$ip;
		WHERE Username='$giocatore'";
		if ($conn->query($sql) === TRUE) {
	    	$flag=$flag+1;
	} else {
	    echo "Error updating record online: " . $conn->error;
	}
	if($flag==2){
		echo 1;
	}



	$conn->close();


	}

	//registrazione amico
	else if($operazione==4){
		$amico=$_GET['amico'];
		$query="INSERT INTO Amicizia VALUES ('$giocatore','$amico')";

		if($conn->query($query)==TRUE){
			echo "1";
		}
		else{
			echo "-1";
		}


	}

	//ricerca  di un utente
	else if($operazione==5){
		$amico=$_GET["amico"];
		$query="SELECT Username,Cognome,Nome 
				WHERE Username=$amico";
		
		$risultato=mysql_query($sql,$conn1);
		if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo $row["username"].";".$row["nome"].";".$row["cognome"];



	    }
	} else {
	    echo "0";
	}
	}

	/*lenco degli amici di un utente */
	else if($operazione==6){
		$sql="SELECT Username1,U1.Nome AS Nome1,U1.Cognome AS Cognome1,U1.IndirizzoIP AS IndirizzoIP1,Username2,U2.Nome AS Nome2,U2.Cognome AS Cognome2,U2.IndirizzoIP AS IndirizzoIP2
			  FROM Amicizia,Utenti AS U1,Utenti AS U2
			  WHERE Amicizia.Username1=U1.Username
			  AND Amicizia.Username1=U2.Username
			  AND Username1='$giocatore'
			  OR Username2='$giocatore'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
	    // output data of each row
		while($row = $result->fetch_assoc()){

	    if($row["Username1"]==$giocatore){
	    	echo $row["Username2"].";".$row["Nome2"].";".$row["Cognome2"].";".$row["IndirizzoIP2"];
	    }
	    else{
	    	echo $row["Username1"].";".$row["Nome1"].";".$row["Cognome1"].";".$row["IndirizzoIP1"];
	    }

	    }



	    }
		} else {
	    echo "0";
		}
		
	



?>