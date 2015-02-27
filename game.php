<?php
	require_once('room.php');
	
	set_time_limit (0);
	// Set the ip and port we will listen on
	$address = '127.0.0.1';
	$port = 9000;
	$max_clients = 2;
	// Array that will hold client information
	$client = array();
	// Create a TCP Stream socket
	$sock = socket_create(AF_INET, SOCK_STREAM, 0);
	// Bind the socket to an address/port
	socket_bind($sock, $address, $port) or die('Could not bind to address');
	// Start listening for connections
	socket_listen($sock);
	// Loop continuously
	while (true) {
	    // Setup clients listen socket for reading
	    $read[0] = $sock;
	    for ($i = 0; $i < $max_clients; $i++)
	    {
	        if (isset($client[$i]))
	        if ($client[$i]['sock']  != null)
	            $read[$i + 1] = $client[$i]['sock'] ;
	    }
	    // Set up a blocking call to socket_select()
	    $ready = socket_select($read, $write = NULL, $except = NULL, $tv_sec = NULL);
	     if a new connection is being made add it to the client array */
	    if (in_array($sock, $read)) {
	        for ($i = 0; $i < $max_clients; $i++)
	        {
	            if (!isset($client[$i])) {
	                $client[$i] = array();
	                $client[$i]['sock'] = socket_accept($sock);
	                echo("Accepting incomming connection...\n");
	                break;
	            }
	            elseif ($i == $max_clients - 1)
	                print ("too many clients");
	        }
	        if (--$ready <= 0)
	            continue;
	    } // end if in_array

	    // If a client is trying to write - handle it now
	    for ($i = 0; $i < $max_clients; $i++) // for each client
	    {
	        if (isset($client[$i]))
	        if (in_array($client[$i]['sock'] , $read))
	        {
	            $input = socket_read($client[$i]['sock'] , 1024);
	            if ($input == null) {
	                // Zero length string meaning disconnected
	                echo("Client disconnected\n");
	                unset($client[$i]);
	            }
	            $n = trim($input);
	            if ($n == 'exit') {
	                echo("Client requested disconnect\n");
	                // requested disconnect
	                socket_close($client[$i]['sock']);
	            }
	            if(substr($n,0,3) == 'say') {
	                //broadcast
	                echo("Broadcast received\n");
	                for ($j = 0; $j < $max_clients; $j++) // for each client
	                {
	                    if (isset($client[$j]))
	                    if ($client[$j]['sock']) {
	                        socket_write($client[$j]['sock'], substr($n, 4, strlen($n)-4).chr(0));
	                    }
	                }
	            } elseif ($input) {
	                echo("Returning stripped input\n");
	                // strip white spaces and write back to user
	                $output = ereg_replace("[ \t\n\r]","",$input).chr(0);
	                socket_write($client[$i]['sock'],$output);
	            }
	        } else {
	            // Close the socket
	            if (isset($client[$i]))
	            echo("Client disconnected\n");
	            if ($client[$i]['sock'] != null){ 
	                socket_close($client[$i]['sock']); 
	                unset($client[$i]); 
	            }
	        }
	    }
	} // end while
	// Close the master sockets
	echo("Shutting down\n");
	socket_close($sock);



?>
