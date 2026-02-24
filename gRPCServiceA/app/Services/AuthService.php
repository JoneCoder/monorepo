<?php

namespace App\Services;

use AuthService\AuthRequest;
use AuthService\AuthServiceClient;

class AuthService
{
    public function checkTokenIsValid(): bool
    {
        $client = new AuthServiceClient('localhost:50051', [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);

        $authRequest = new AuthRequest();
        $authRequest->setToken(request()->getAuthToken());
        list($response, $status) = $client->AuthCheck($authRequest)->wait();
        if ($status->code !== \Grpc\STATUS_OK) {
            return false;
        }
        return true;
    }
}
