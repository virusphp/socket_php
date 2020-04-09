<?php

$host = "127.0.0.1";
$port = 9001;
$message = " IP :". $_SERVER['REMOTE_ADDR'] . " Browser :". $_SERVER['HTTP_USER_AGENT'];
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("[".date('Y-m-d H:i:s')."]from Client: Could not create socket\n");
$result = socket_connect($socket, $host, $port) or die("[".date('Y-m-d H:i:s')."]from Client: Unable to connect server\n");

socket_write($socket, $message, strlen($message)) or die("from Client: Unable send data to server\n");

$result = socket_read($socket, 1024) or die("from Client: Could not read response from server\n");

socket_write($socket, "END", 3) or die("from Client: Could not end sesssion\n");
socket_close($socket);
$result = trim($result);
echo "\n Message return : ". $result;