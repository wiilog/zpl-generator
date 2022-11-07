<?php

namespace ZplGenerator\Client;

use JetBrains\PhpStorm\Pure;
use RuntimeException;

class CloudClientException extends RuntimeException {

    public const PRINT_FAILURE = "PRINT_FAILURE";

    public const BAD_REQUEST = "BAD_REQUEST";
    public const INVALID_AUTHENTICATION = "INVALID_AUTHENTICATION";
    public const LOW_ACCOUNT_BALANCE = "LOW_ACCOUNT_BALANCE";
    public const PRINTER_NOT_FOUND = "PRINTER_NOT_FOUND";
    public const QUOTA_VIOLATION = "QUOTA_VIOLATION";

    public const STATUS_MESSAGE = [
        -1 => self::PRINT_FAILURE,

        400 => self::BAD_REQUEST,
        401 => self::INVALID_AUTHENTICATION,
        403 => self::LOW_ACCOUNT_BALANCE,
        404 => self::PRINTER_NOT_FOUND,
        429 => self::QUOTA_VIOLATION,
    ];

    public function __construct(int $code) {
        parent::__construct(self::STATUS_MESSAGE[$code], $code);
    }

}