<?php
// GENERATED CODE -- DO NOT EDIT!

namespace AuthService;

/**
 */
class AuthServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \AuthService\AuthRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall<\AuthService\AuthResponse>
     */
    public function AuthCheck(\AuthService\AuthRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/AuthService.AuthService/AuthCheck',
        $argument,
        ['\AuthService\AuthResponse', 'decode'],
        $metadata, $options);
    }

}
