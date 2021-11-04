<?php

namespace ZplGenerator\Printer;

use Zebra\CommunicationException;

class Client {

    protected $socket;

    public function __construct(string $host, int $port = 9100) {
        $this->connect($host, $port);
    }

    public function __destruct() {
        $this->disconnect();
    }

    public static function printer(string $host, int $port = 9100): self {
        return new static($host, $port);
    }

    protected function connect(string $host, int $port): void {
        $this->socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if(!$this->socket || !$this->connectWithTimeout($this->socket, $host, $port)) {
            $error = $this->getLastError();
            throw new CommunicationException($error["message"], $error["code"]);
        }
    }

    private function connectWithTimeout($socket, string $host, int $port): bool {
        $connexionsPerSecond = 100;
        $timeout = 10;

        socket_set_nonblock($socket);

        for($i=0; $i<($timeout * $connexionsPerSecond); $i++) {
            @socket_connect($socket, $host, $port);
            if(socket_last_error($socket) == SOCKET_EISCONN) {
                break;
            }

            usleep(1000000 / $connexionsPerSecond);
        }

        socket_set_block($socket);

        return socket_last_error($socket) == SOCKET_EISCONN;
    }

    protected function disconnect(): void {
        @socket_close($this->socket);
    }

    public function send(string $zpl): void {
        if(false === @socket_write($this->socket, $zpl)) {
            $error = $this->getLastError();
            throw new CommunicationException($error['message'], $error['code']);
        }
    }

    protected function getLastError(): array {
        $code = socket_last_error($this->socket);
        $message = socket_strerror($code);

        return compact('code', 'message');
    }

}
