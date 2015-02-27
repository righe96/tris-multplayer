<?php
	$servername = "192.168.1.2";
	$username = "alessio";
	$password = "milan96";
	$dbname="trismultiplayer";
	$Uusername=$_GET["username"];
	$Upassword = $_GET["password"];
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql="SELECT *
			FROM Utenti
			WHERE Username='$Uusername'
			AND Password='$Upassword'";
	$result = $conn->query($sql);
		
		if ($result->num_rows >0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {

		        $data  = array(
								'result'=>'OK',
								'username' => $row["Username"], 
								'password' =>$row["Password"],
								'nome'=>$row["Nome"],
								'cognome'=>$row["Cognome"],
								'punteggio'=>$row["Punteggio"],

					);

				
				$json = json_encode($data);
				echo $json;


		    }
		} else {
		    echo "0";
		}
	
?>