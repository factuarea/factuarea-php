<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\OAuthOriginals;
use Closure;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use LogicException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use WeakMap;
/** Native OAuth dispatch boundary, including generated methods. No automatic refresh or mutation replay. */
final class OAuthBearerClient implements ClientInterface
{
    /** @var WeakMap<RequestInterface,bool> */
    private WeakMap $sent;
    public function __construct(private readonly ClientInterface $client, private readonly Closure $source)
    {
        $this->sent = new WeakMap();
    }
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        [$request, $options] = $this->authorize($request, $options);

        return $this->client->send($request, $options);
    }
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        [$request, $options] = $this->authorize($request, $options);

        return $this->client->sendAsync($request, $options);
    }
    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        return $this->send(new Request($method, $uri), $options);
    }
    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        return $this->sendAsync(new Request($method, $uri), $options);
    }
    public function getConfig(?string $option = null): mixed
    {
        return $this->client->getConfig($option);
    }
    /** @return array{RequestInterface,array<string,mixed>} */
    private function authorize(RequestInterface $request, array $options): array
    {
        if (in_array(strtoupper($request->getMethod()), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            if (isset($this->sent[$request])) {
                throw new LogicException('OAuth mutation already dispatched; recover the native original with GET.');
            }
            $this->sent[$request] = true;
        }
        // SecurityClient's generated build-time headers must not overwrite the current native issuer token.
        foreach (array_keys($options['headers'] ?? []) as $name) {
            if (is_string($name) && in_array(strtolower($name), ['authorization', 'x-api-key'], true)) {
                unset($options['headers'][$name]);
            }
        }
        $token = ($this->source)()->bearerAuth;
        $request = $request->withHeader('Authorization', 'Bearer '.$token)->withoutHeader('X-API-Key');
        $options['allow_redirects'] = false;

        return [$request, $options];
    }
}
