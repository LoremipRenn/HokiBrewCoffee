<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public string $serverKey;
    public string $clientKey;
    public bool $isProduction;
    public bool $isSanitized = true;
    public bool $is3ds = true;

    public function __construct()
    {
        parent::__construct();
        $this->serverKey    = (string) env('MIDTRANS_SERVER_KEY', '');
        $this->clientKey    = (string) env('MIDTRANS_CLIENT_KEY', '');
        $this->isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
    }

    public function isConfigured(): bool
    {
        return ! empty($this->serverKey)
            && ! empty($this->clientKey)
            && ! str_contains($this->serverKey, 'YOUR_SANDBOX')
            && ! str_contains($this->clientKey, 'YOUR_SANDBOX');
    }

    public function snapJsUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }
}
