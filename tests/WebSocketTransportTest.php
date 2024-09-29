<?php

namespace Gri3li\SymfonyMessengerWebSocketTransport\Tests;

use Amp\Websocket\Client;
use Gri3li\SymfonyMessengerWebSocketTransport\WebSocketTransport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Exception\TransportException;

class WebSocketTransportTest extends TestCase
{
    private const SEND_COUNT = 10;

    public function testEcho(): void
    {
        $connection = Client\connect('wss://echo.websocket.in');
        $transport = new WebSocketTransport($connection);
        $asserts = [];
        $i = 0;
        while ($i < self::SEND_COUNT) {
            $message = ['number' => $i];
            $asserts[$i]['expect'] = $transport->send(new Envelope((object)$message));
            $i++;
        }
        $j = 0;
        while (true) {
            try {
                foreach ($transport->get() as $item) {
                    if (is_object($item)) {
                        $asserts[$j]['actual'] = $item;
                        $j++;
                    }
                }
            } catch (TransportException $exception) {
                // Skipping exceptions caused by debug messages from websocket echo server
                if (!$exception->getPrevious() instanceof MessageDecodingFailedException) {
                    throw $exception;
                }
            }
            if ($j >= self::SEND_COUNT) {
                break;
            }
        }
        foreach ($asserts as $assert) {
            $this->assertEquals($assert['expect'], $assert['actual']);
        }
    }
}
