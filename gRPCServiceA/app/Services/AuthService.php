<?php

namespace App\Services;

use AuthService\AuthRequest;
use AuthService\AuthServiceClient;

class AuthService
{
    public function checkTokenIsValid(): bool
    {
        $client = new AuthServiceClient('authservice:50051', [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);

        $authRequest = new AuthRequest();
        $authRequest->setToken(request()->bearerToken());
        list($response, $status) = $client->AuthCheck($authRequest)->wait();
        if ($status->code !== \Grpc\STATUS_OK) {
            return false;
        }
        return true;
    }
}
