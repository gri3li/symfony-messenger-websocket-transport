Symfony Messenger WebSocket Transport
=====================================

This package provides a WebSocket transport for
the [Symfony Messenger](https://symfony.com/doc/current/components/messenger.html) component, enabling real-time
communication between Symfony Messenger and external services via WebSocket connections.

Installation
------------

Install the package via Composer:

```bash
composer require gri3li/symfony-messenger-websocket-transport
```

Usage
-----

Since all messages will be serialized and deserialized as instances of StdClass, you will most likely need to provide
custom implementations of the interfaces:

- `SendersLocatorInterface`: Defines which sender will be used for dispatching a message.
- `HandlersLocatorInterface`: Defines which handler will process the message.
