<?php

class server_socket 
{
    protected $host;
    protected $port;
    protected $buffer;
    protected $connected;
    protected $bind_result;
    protected $listen_result;
    protected $new_socket;
    protected $to_client;
    protected $form_client;
    protected $write_len;

    function __construct($host="127.0.0.1", $port=9001, $max=0)
    {
       $this->host = $host;
       $this->port = $port;
       $this->buffer = 1024;
       $this->connected = false;
       echo " Start listening. . . \n\r";
       $this->server_created_socket();
    }

    public function server_created_socket()
    {
        set_time_limit(0);
        $this->connected = socket_create(AF_INET, SOCK_STREAM, 0) or die("Tidak bisa membua socket");
        if ($this->connected) {
            $this->bind_result = socket_bind($this->connected, $this->host, $this->port) or die("Tidak bisa binding to port");
            if ($this->bind_result) {
                $this->listen_result = socket_listen($this->connected, 3) or die("Tidak bisa mendengar (listen)");
                $this->open_listening();
            }
        } else {
            self::__construct();
        }
    }

    private function listen_loop()
    {
        $this->new_socket = socket_accept($this->connected);
        if ($this->new_socket) {
            return true;
        } else {
            return false;
        }
    }

    public function open_listening()
    {
        $this->listen_loop();
        $this->form_client = socket_read($this->new_socket, $this->buffer);
        $this->form_client = trim($this->form_client);
        $this->str_cmd[date('Y-m-d h:i:s')] = '';
        if ($this->form_client) {
            $this->str_cmd[date('Y-m-d h:i:s')] = $this->form_client;
            self::wrtie_callback_to_client();
            self::console();
            socket_set_block($this->connected);
            if (!$this->connected) $this->server_created_socket();
            $this->server_created_socket();
        }
    }

    function wrtie_callback_to_client() 
    {
        $this->to_client = $this->form_client;
        $this->write_len = socket_write($this->new_socket, $this->to_client, strlen($this->to_client)) or die("Tidak bisa menulis (Write)");
    }

    public function console()
    {
        if (is_array($this->str_cmd)) {
            $n = 0;
            foreach($this->str_cmd as $key => $value) {
                echo "[". $key ."] : ".$value." \n\r";
                $n++;
            }
        }
    }
}

ob_implicit_flush(true);
$ok = new server_socket($host="127.0.0.1",$port=9001,$max=0);