<?php

namespace ZplGenerator\Client;

use Socket;
use Zebra\CommunicationException;

class SocketClient implements Client {

    protected $socket;

    private ?string $error_code = null;
    private ?string $error_message = null;

    public function __construct(string $host, int $port, int $timeout) {
        $this->connect($host, $port, $timeout);
    }

    public function __destruct() {
        $this->disconnect();
    }

    public static function create(string $host, int $port, int $timeout): self {
        return new SocketClient($host, $port, $timeout);
    }

    protected function connect(string $host, int $port, int $timeout): void {
        $this->socket = fsockopen($host, $port, $this->error_code, $this->error_message, $timeout);

        if(!$this->socket) {
            $error = $this->getLastError();
            throw new CommunicationException($error["message"], $error["code"]);
        }
    }

    protected function disconnect(): void {
        pclose($this->socket);
    }

    public function send(string $zpl): void {
        if(false === @fwrite($this->socket, $zpl)) {
            throw new CommunicationException($this->error_message, $this->error_code);
        }
    }

    protected function getLastError(): array {
        $code = $this->error_code;
        $message = $this->error_message;

        return compact('code', 'message');
    }

}
