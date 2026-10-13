<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Conversations;

use Factuarea\Sdk\Custom\Crm\WireModel;

final class Participant extends WireModel
{
    public const SCHEMA = Schemas::PARTICIPANT;

    public const FIELDS = ['email' => ['property' => 'email', 'type' => ['scalar' => 'string']], 'name' => ['property' => 'name', 'type' => ['scalar' => 'string']]];

    public function __construct(
        public readonly string $email,
        public readonly ?string $name,
    ) {
        $this->validate();
        Validation::email($email);
    }
}
