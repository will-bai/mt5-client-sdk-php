<?php

namespace mt5\client;

require_once __DIR__ . '/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = __DIR__.'/gen-php';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__);
$loader->registerDefinition('mt5', $GEN_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use mt5\MT5ServiceClient;

class MT5Client
{
	private $r_buf_size;
	private $w_buf_size;
	private $send_time_out;
	private $recv_time_out;
	private $transport;
	private $client;

    public function __construct()
    {
        $this->r_buf_size = 1024;
        $this->w_buf_size = 1024;
        $this->send_time_out = 60;
        $this->recv_time_out = 60;

        $socket = new TSocket("127.0.0.1", 9090);
        $socket->setSendTimeout($this->send_time_out * 1000);
        $socket->setRecvTimeout($this->recv_time_out * 1000);

        $this->transport = new TBufferedTransport($socket, $this->r_buf_size, $this->w_buf_size);
        $protocol = new TBinaryProtocolAccelerated($this->transport);
        $this->client = new MT5ServiceClient($protocol);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array( array($this->client, $name), $arguments);
    }

    public function open()
    {
        $this->transport->open();
    }

    public function close()
    {
        $this->transport->close();
    }
}