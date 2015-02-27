<?php
	$servername = "192.168.1.2";
	$username = "alessio";
	$password = "milan96";
	$dbname="trismultiplayer";
	$Uusername=$_GET["username"];
	$Upassword = $_GET["password"];
	$Unome= $_GET["nome"];
	$Ucognome= $_GET["cognome"];

	// Create connection
	$conn = new mysqli($servername, $username, $password,$dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	else{
			//verifica se è gia presente il username
			$conn1 = mysql_connect($servername, $username, $password);
      		$sql="SELECT * FROM utenti WHERE username='$Uusername'";
			mysql_select_db($dbname,$conn1);
			$risultato=mysql_query($sql,$conn1);
			if(mysql_num_rows($risultato)==1){
				echo "-1";
			}
			else{

			$query = "INSERT INTO utenti (Username,Password,Cognome,Nome,IndirizzoIP,Online)
				VALUES (?,?,?,?,?,?)";
			$stmt = $conn->prepare($query);
			$online=1;

			//Test if it is a shared client
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
			
			

			//The value of $ip at this point would look something like: "192.0.34.166"
			$IndirizzoIP = $ip = sprintf('%u', ip2long($ip));
			
			
			


			$stmt->bind_param("ssssii", $Uusername, $Upassword,$Ucognome,$Unome,$IndirizzoIP,$online);
			if($stmt->execute()==TRUE){
				
			$data  = array(
							'result'=>'OK',
							'username' => $Uusername, 
							'password' =>$Upassword,
							'nome'=>$Unome,
							'cognome'=>$Ucognome,
							'punteggio'=>0,

				);

			
			}
			else{
				$data=array('result'=>'KO',
							'username'=>'null',
							'password'=>'null',
							'nome'=>'null',
							'cognome'=>'null',
							);
				}
			$json = json_encode($data);
			echo $json;
			
		
				}
	}





	$conn->close();


?>
