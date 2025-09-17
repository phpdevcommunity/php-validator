<?php

namespace Test\PhpDevCommunity\Validator\Helper;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class Request
{

    public static function create(array $postData): \Psr\Http\Message\ServerRequestInterface
    {
        $request = new class implements \Psr\Http\Message\ServerRequestInterface {

            private $postData = [];

            public function getUploadedFiles(): array
            {
                return [];
            }

            public function getParsedBody(): array
            {
                return $this->postData;
            }

            public function withParsedBody($data): \Psr\Http\Message\ServerRequestInterface
            {
                $this->postData = $data;
                return clone $this;
            }

            public function getProtocolVersion(): string
            {
                // TODO: Implement getProtocolVersion() method.
            }

            public function withProtocolVersion(string $version): MessageInterface
            {
                // TODO: Implement withProtocolVersion() method.
            }

            public function getHeaders(): array
            {
                // TODO: Implement getHeaders() method.
            }

            public function hasHeader(string $name): bool
            {
                // TODO: Implement hasHeader() method.
            }

            public function getHeader(string $name): array
            {
                // TODO: Implement getHeader() method.
            }

            public function getHeaderLine(string $name): string
            {
                // TODO: Implement getHeaderLine() method.
            }

            public function withHeader(string $name, $value): MessageInterface
            {
                // TODO: Implement withHeader() method.
            }

            public function withAddedHeader(string $name, $value): MessageInterface
            {
                // TODO: Implement withAddedHeader() method.
            }

            public function withoutHeader(string $name): MessageInterface
            {
                // TODO: Implement withoutHeader() method.
            }

            public function getBody(): StreamInterface
            {
                // TODO: Implement getBody() method.
            }

            public function withBody(StreamInterface $body): MessageInterface
            {
                // TODO: Implement withBody() method.
            }

            public function getRequestTarget(): string
            {
                // TODO: Implement getRequestTarget() method.
            }

            public function withRequestTarget(string $requestTarget): RequestInterface
            {
                // TODO: Implement withRequestTarget() method.
            }

            public function getMethod(): string
            {
                // TODO: Implement getMethod() method.
            }

            public function withMethod(string $method): RequestInterface
            {
                // TODO: Implement withMethod() method.
            }

            public function getUri(): UriInterface
            {
                // TODO: Implement getUri() method.
            }

            public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
            {
                // TODO: Implement withUri() method.
            }

            public function getServerParams(): array
            {
                // TODO: Implement getServerParams() method.
            }

            public function getCookieParams(): array
            {
                // TODO: Implement getCookieParams() method.
            }

            public function withCookieParams(array $cookies): ServerRequestInterface
            {
                // TODO: Implement withCookieParams() method.
            }

            public function getQueryParams(): array
            {
                // TODO: Implement getQueryParams() method.
            }

            public function withQueryParams(array $query): ServerRequestInterface
            {
                // TODO: Implement withQueryParams() method.
            }

            public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
            {
                // TODO: Implement withUploadedFiles() method.
            }

            public function getAttributes(): array
            {
                // TODO: Implement getAttributes() method.
            }

            public function getAttribute(string $name, $default = null)
            {
                // TODO: Implement getAttribute() method.
            }

            public function withAttribute(string $name, $value): ServerRequestInterface
            {
                // TODO: Implement withAttribute() method.
            }

            public function withoutAttribute(string $name): ServerRequestInterface
            {
                // TODO: Implement withoutAttribute() method.
            }
        };
        $request->withParsedBody($postData);
        return $request;
    }
}
