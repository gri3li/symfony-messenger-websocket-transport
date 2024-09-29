<?php

namespace Gri3li\SymfonyMessengerWebSocketTransport;

use Amp\Websocket\Client;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class WebSocketTransportFactory implements TransportFactoryInterface
{
    public function createTransport(
        #[\SensitiveParameter] string $dsn,
        array $options,
        SerializerInterface $serializer
    ): TransportInterface {
        unset($options['transport_name']);
        try {
            $connection = Client\connect($dsn);
        } catch (\Throwable $throwable) {
            throw new TransportException($throwable->getMessage(), 0, $throwable);
        }

        return new WebSocketTransport($connection, $serializer);
    }

    public function supports(#[\SensitiveParameter] string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'ws://') || str_starts_with($dsn, 'wss://');
    }
}
