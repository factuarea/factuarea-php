<?php

declare(strict_types=1);

namespace Factuarea\Sdk\Custom\Crm\Documents;

/** Only existing native v1 operations; receipt state has no public endpoint. */
enum DocumentOperation: string
{
    case Upload = 'crm_documents.upload';
    case Replace = 'crm_documents.replace_version';
    case Share = 'crm_documents.share';
    case RevokeShare = 'crm_documents.revoke_share';
    case Archive = 'crm_documents.archive';
    case CreateQuote = 'crm_opportunitydocuments.create_quote';
    case Link = 'crm_opportunitydocuments.link_document';
    case Unlink = 'crm_opportunitydocuments.unlink_document';
    public function library(): bool
    {
        return in_array($this, [self::Upload, self::Replace, self::Share, self::RevokeShare, self::Archive], true);
    }
    public function status(): int
    {
        return in_array($this, [self::Upload, self::CreateQuote, self::Link], true) ? 201 : 200;
    }
}
