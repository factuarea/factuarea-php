<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Recipient extends WireModel
{
    public const SCHEMA = Schemas::RECIPIENT;

    public const FIELDS = ['email' => ['property' => 'email', 'type' => ['scalar' => 'string']], 'name' => ['property' => 'name', 'type' => ['scalar' => 'string']], 'kind' => ['property' => 'kind', 'type' => ['scalar' => 'string']]];

    public function __construct(
        public readonly string $email,
        public readonly ?string $name,
        public readonly string $kind,
    ) {
        $this->validate();
        Validation::email($email);
    }
}
