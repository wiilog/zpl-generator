<?php

namespace ZplGenerator\Printer;

use Zebra\CommunicationException;

class Client {

    /**
     * The endpoint.
     *
     * @var resource
     */
    protected $socket;

    public function __construct(string $host, int $port = 9100) {
        $this->connect($host, $port);
    }

    /**
     * Destroy an instance.
     */
    public function __destruct() {
        $this->disconnect();
    }

    /**
     * Create an instance statically.
     *
     * @param string $host
     * @param int $port
     * @return Client
     */
    public static function printer(string $host, int $port = 9100): self {
        return new static($host, $port);
    }

    /**
     * Connect to printer.
     *
     * @param string $host
     * @param int $port
     * @throws CommunicationException if the connection fails.
     */
    protected function connect(string $host, int $port): void {
        $this->socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $timeout = @stream_set_timeout($this->socket, 10);

        if(!$this->socket || !$timeout || !@socket_connect($this->socket, $host, $port)) {
            $error = $this->getLastError();
            throw new CommunicationException($error['message'], $error['code']);
        }
    }

    /**
     * Close connection to printer.
     */
    protected function disconnect(): void {
        @socket_close($this->socket);
    }

    /**
     * Send ZPL data to printer.
     *
     * @param string $zpl
     * @throws CommunicationException if writing to the socket fails.
     */
    public function send(string $zpl): void {
        if(false === @socket_write($this->socket, $zpl)) {
            $error = $this->getLastError();
            throw new CommunicationException($error['message'], $error['code']);
        }
    }

    /**
     * Get the last socket error.
     *
     * @return array
     */
    protected function getLastError(): array {
        $code = socket_last_error($this->socket);
        $message = socket_strerror($code);

        return compact('code', 'message');
    }

}
