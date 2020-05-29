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
    private $transport;
    private $client;
    private $host;
    private $port;
    private $send_time_out = 60000;
    private $recv_time_out = 60000;
    private $r_buf_size = 1024;
    private $w_buf_size = 1024;

    public function __construct()
    {

    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param int $send_time_out
     */
    public function setSendTimeOut($send_time_out)
    {
        $this->send_time_out = $send_time_out;
        return $this;
    }

    /**
     * @param int $recv_time_out
     */
    public function setRecvTimeOut($recv_time_out)
    {
        $this->recv_time_out = $recv_time_out;
        return $this;
    }

    /**
     * @param int $r_buf_size
     */
    public function setRBufSize($r_buf_size)
    {
        $this->r_buf_size = $r_buf_size;
        return $this;
    }

    /**
     * @param int $w_buf_size
     */
    public function setWBufSize($w_buf_size)
    {
        $this->w_buf_size = $w_buf_size;
        return $this;
    }

    public function instantiation()
    {
        $socket = new TSocket($this->host, $this->port);
        $socket->setSendTimeout($this->send_time_out);
        $socket->setRecvTimeout($this->recv_time_out);

        $this->transport = new TBufferedTransport($socket, $this->r_buf_size, $this->w_buf_size);
        $protocol = new TBinaryProtocolAccelerated($this->transport);
        $this->client = new MT5ServiceClient($protocol);
        return $this;
    }

    public function open()
    {
        $this->transport->open();
        return $this;
    }

    public function close()
    {
        $this->transport->close();
        return $this;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->client, $name), $arguments);
    }
}