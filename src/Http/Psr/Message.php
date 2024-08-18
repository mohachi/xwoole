<?php

declare(strict_types=1);

namespace Mohachi\Xwoole\Http\Psr;

use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    public $headers = [];

    protected $protocolVersion = '1.1';

    protected $stream;

    public function __construct($stream = null)
    {
        if ($stream === null)
        {
            $stream = new Stream('php://memory', 'wb+');
        }

        $this->withBody($stream);
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): static
    {
        $message                  = clone $this;
        $message->protocolVersion = $version;
        return $message;
    }

    public function getHeaders(): array
    {
        $headers = [];
        foreach ($this->headers as $header => $line)
        {
            $headers[$header] = is_array($line) ? $line : [$line];
        }
        return $headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader($name): array
    {
        return $this->hasHeader($name) ? $this->headers[strtolower($name)] : [];
    }

    public function getHeaderLine($name): string
    {
        $value = $this->getHeader($name);

        if (empty($value))
        {
            return '';
        }

        return is_array($value) ? implode(',', $value) : $value;
    }

    public function withHeader($name, $value): static
    {
        if (!is_string($name) || !is_string($value) && !is_array($value) || $name === '' || $value !== '' && empty($value))
        {
            throw new InvalidArgumentException('Header is not validate.');
        }
        $message = clone $this;

        if (is_array($value))
        {
            $message->headers[strtolower($name)] = $value;
        }
        else
        {
            $message->headers[strtolower($name)] = [$value];
        }

        return $message;
    }

    public function withHeaders(array $headers)
    {
        $message = clone $this;
        foreach ($headers as $key => $header)
        {
            if (is_array($header))
            {
                foreach ($header as $item)
                {
                    $message = $message->withAddedHeader($key, $item);
                }
            }
            else
            {
                $message = $message->withHeader($key, $header);
            }
        }

        return $message;
    }

    public function withAddedHeader($name, $value): static
    {
        if (!is_string($name) || !is_string($value) && !is_array($value) || empty($name) || $value !== '' && $value !== '0' && empty($value))
        {
            throw new InvalidArgumentException('Header is not validate.');
        }
        
        $message = clone $this;
        
        if (is_array($value))
        {
            foreach ($value as $item)
            {
                $message->headers[strtolower($name)][] = $item;
            }
        }
        else
        {
            $message->headers[strtolower($name)][] = $value;
        }

        return $message;
    }

    public function withoutHeader($name): static
    {
        $name = strtolower($name);

        if (!$this->hasHeader($name))
        {
            return $this;
        }

        unset($this->headers[$name]);

        return $this;
    }

    public function getBody(): StreamInterface
    {
        return $this->stream;
    }

    public function withBody($stream): static
    {
        $message         = clone $this;
        $message->stream = $stream;
        return $message;
    }

    protected function setHeaders(array $headers): void
    {
        $this->headers = $this->withHeaders($headers)->getHeaders();
    }
}
