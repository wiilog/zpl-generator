<?php

namespace ZplGenerator\Client;

use ZplGenerator\Printer\Printer;

interface Client {

    function send(string $zpl): void;

}
