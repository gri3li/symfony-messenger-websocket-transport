<?php

namespace Gri3li\SymfonyMessengerWebSocketTransport;

use Amp\Websocket\Client\WebsocketConnection;
use Amp\Websocket\WebsocketMessage;
use Gri3li\SymfonyMessengerSerializerPlain\PlainSerializer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

readonly class WebSocketReceiver implements ReceiverInterface
{
    public function __construct(
        private WebsocketConnection $connection,
        private SerializerInterface $serializer = new PlainSerializer(),
    ) {
    }

    public function get(): iterable
    {
        try {
            /** @var WebsocketMessage $message */
            foreach ($this->connection->receive() as $message) {
                yield $this->serializer->decode(['body' => (string)$message]);
            }
        } catch (\Throwable $throwable) {
            throw new TransportException($throwable->getMessage(), 0, $throwable);
        }
    }

    public function ack(Envelope $envelope): void
    {
    }

    public function reject(Envelope $envelope): void
    {
    }
}
