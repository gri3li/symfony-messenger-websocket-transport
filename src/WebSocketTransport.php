<?php

namespace Gri3li\SymfonyMessengerWebSocketTransport;

use Amp\Websocket\Client\WebsocketConnection;
use Gri3li\SymfonyMessengerSerializerPlain\PlainSerializer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class WebSocketTransport implements TransportInterface
{
    private WebSocketReceiver $receiver;
    private WebSocketSender $sender;

    public function __construct(
        private readonly WebsocketConnection $connection,
        private readonly SerializerInterface $serializer = new PlainSerializer(),
    ) {
    }

    public function send(Envelope $envelope): Envelope
    {
        return $this->getSender()->send($envelope);
    }

    public function get(): iterable
    {
        yield from $this->getReceiver()->get();
    }

    public function ack(Envelope $envelope): void
    {
        $this->getReceiver()->ack($envelope);
    }

    public function reject(Envelope $envelope): void
    {
        $this->getReceiver()->reject($envelope);
    }

    private function getReceiver(): WebSocketReceiver
    {
        return $this->receiver ??= new WebSocketReceiver($this->connection, $this->serializer);
    }

    private function getSender(): WebSocketSender
    {
        return $this->sender ??= new WebSocketSender($this->connection, $this->serializer);
    }
}
