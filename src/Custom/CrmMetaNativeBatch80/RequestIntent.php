<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\CrmMetaNativeBatch80;

/** Retains actual human choices and their exact first wire, without authority inputs. */
final readonly class RequestIntent
{
    public string $path;
    public function __construct(public string $action, #[\SensitiveParameter] public ?string $firstWire = null, public ?string $connection = null, public ?string $form = null)
    {
        if (!isset(Contract::ROUTES[$action]) || ($connection !== null && !Contract::uuid($connection)) || ($form !== null && !Contract::uuid($form))) {
            Contract::invalid();
        }
        $suffix = Contract::ROUTES[$action][1];
        if (str_contains($suffix, '{connection}') !== ($connection !== null) || str_contains($suffix, '{form}') !== ($form !== null)) {
            Contract::invalid();
        }
        if ($firstWire !== null && strlen($firstWire) > 1048576) {
            Contract::invalid();
        }
        Contract::input($action, $this->body());
        $this->path = '/crm/connections/meta-lead-ads/'.ltrim(str_replace(['{connection}', '{form}'], [$connection ?? '', $form ?? ''], $suffix), '/');
    }
    public function body(): ?\stdClass
    {
        if ($this->firstWire === null) {
            return null;
        }
        try {
            return Contract::object(json_decode($this->firstWire, false, 32, JSON_THROW_ON_ERROR));
        } catch (\Throwable) {
            Contract::invalid();
        }
    }
    public static function fromBody(string $action, #[\SensitiveParameter] array|\stdClass $body, ?string $connection = null, ?string $form = null): self
    {
        try {
            $raw = json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\Throwable) {
            Contract::invalid();
        }

        return new self($action, $raw, $connection, $form);
    }
}
