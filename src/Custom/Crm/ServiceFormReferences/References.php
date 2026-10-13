<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\ServiceFormReferences;

use Factuarea\Sdk\Custom\Crm\WireModel;

/** Fully admitted collections. A missing/hidden field is never turned into an empty collection or grant. */
final class References extends WireModel
{
    public const SCHEMA = Schemas::REFERENCES;
    public const FIELDS = ['queues' => ['property' => 'queues', 'type' => ['items' => ['model' => QueueReference::class]]],
        'policies' => ['property' => 'policies', 'type' => ['items' => ['model' => PolicyReference::class]]],
        'calendars' => ['property' => 'calendars', 'type' => ['items' => ['model' => CalendarReference::class]]],
        'responsible_users' => ['property' => 'responsibleUsers', 'type' => ['items' => ['model' => ResponsibleUserReference::class]]],
        'authorization_version' => ['property' => 'authorizationVersion', 'type' => ['scalar' => 'string']]];
    /** @param list<QueueReference> $queues
     * @param list<PolicyReference> $policies
     * @param list<CalendarReference> $calendars
     * @param list<ResponsibleUserReference> $responsibleUsers */
    public function __construct(public readonly array $queues, public readonly array $policies,
        public readonly array $calendars, public readonly array $responsibleUsers, public readonly string $authorizationVersion)
    {
        $this->validate();
        if (trim($authorizationVersion) === '') {
            throw new \InvalidArgumentException('Retain the exact current native authorization version.');
        }
        self::unique(array_map(static fn (QueueReference $item): string => $item->id, $queues));
        self::unique(array_map(static fn (PolicyReference $item): string => $item->id, $policies));
        self::unique(array_map(static fn (CalendarReference $item): string => $item->id, $calendars));
        self::unique(array_map(static fn (ResponsibleUserReference $item): string => $item->id, $responsibleUsers));
    }
    /** @param list<string> $ids */
    private static function unique(array $ids): void
    {
        if (count($ids) !== count(array_unique($ids))) {
            throw new \InvalidArgumentException('The native reference collection cannot repeat an identity.');
        }
    }
}
