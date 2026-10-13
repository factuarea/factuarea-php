<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm;

use Factuarea\Sdk\Factuarea;
use Factuarea\Sdk\SDKConfiguration;

/** Additive CRM entry point sharing the configured canonical SDK instance. */
final readonly class CrmClient
{
    private Transport $transport;

    private SDKConfiguration $configuration;

    public ContactPeople $contactPeople;

    public Leads $leads;

    public Pipelines $pipelines;

    public KnowledgeBase $knowledgeBase;

    public PublicHelpCenter $publicHelpCenter;

    public function __construct(Factuarea $sdk)
    {
        $this->configuration = $sdk->sdkConfiguration;
        $this->transport = new Transport($sdk->sdkConfiguration);
        $transport = $this->transport;
        $this->contactPeople = new ContactPeople($transport);
        $this->leads = new Leads($transport);
        $this->pipelines = new Pipelines($transport);
        $this->knowledgeBase = new KnowledgeBase($transport);
        $this->publicHelpCenter = new PublicHelpCenter($transport);
    }

    /** Native paths are supplied explicitly; this factory activates no server capability. */
    public function sequences(Sequence\RequiredPaths $paths): Sequence\SequenceClient
    {
        return new Sequence\SequenceClient($this->transport, $paths);
    }

    /** Uses only the supplied native surface and the SAME canonical configured transport. */
    public function taskTimeBilling(TaskTimeBilling\RequiredPaths $paths): TaskTimeBilling\TaskTimeBillingClient
    {
        return new TaskTimeBilling\TaskTimeBillingClient($this->transport, $paths);
    }

    public function bookings(Bookings\RequiredPaths $paths): Bookings\BookingClient
    {
        return new Bookings\BookingClient($this->transport, $this->configuration, $paths);
    }

    /** Requires mounted native read paths; the server independently admits the current credential and field masks. */
    public function serviceInsights(ServiceInsights\RequiredPaths $paths): ServiceInsights\ServiceInsightsClient
    {
        return new ServiceInsights\ServiceInsightsClient($this->transport, $paths);
    }

    /** Requires the actual mounted V1 paths and shares the SAME canonical configured transport. */
    public function verticalPacks(VerticalPacks\RequiredPaths $paths): VerticalPacks\VerticalPackClient
    {
        return new VerticalPacks\VerticalPackClient($this->transport, $paths);
    }


    /** Supplies the current protected V1 path and shares the SAME configured transport. */
    public function serviceFormReferences(ServiceFormReferences\RequiredPaths $paths): ServiceFormReferences\ServiceFormReferencesClient
    {
        return new ServiceFormReferences\ServiceFormReferencesClient($this->transport, $paths);
    }


    /** Native Opportunity read paths share the SAME configured SDK transport and current server admission. */
    public function opportunities(): Opportunities\OpportunitiesClient
    {
        return new Opportunities\OpportunitiesClient($this->transport);
    }


    /** Native Conversation read paths share the SAME configured SDK transport; server admission remains current. */
    public function conversations(): Conversations\ConversationsClient
    {
        return new Conversations\ConversationsClient($this->transport);
    }
}
