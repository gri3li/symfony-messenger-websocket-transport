<?php

namespace Gri3li\SymfonyMessengerWebSocketTransport;

use Amp\Websocket\Client\WebsocketConnection;
use Gri3li\SymfonyMessengerSerializerPlain\PlainSerializer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

readonly class WebSocketSender implements SenderInterface
{
    public function __construct(
        private WebsocketConnection $connection,
        private SerializerInterface $serializer = new PlainSerializer(),
    ) {
    }

    public function send(Envelope $envelope): Envelope
    {
        try {
            ['body' => $data] = $this->serializer->encode($envelope);
            $this->connection->sendText($data);
        } catch (\Throwable $throwable) {
            throw new TransportException($throwable->getMessage(), 0, $throwable);
        }

        return $envelope;
    }
}
