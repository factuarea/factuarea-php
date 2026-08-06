# CustomField

A typed custom field as an ordered `{field, value}` pair. Distinct from `metadata` (a free key→value map): use `custom_fields` for structured, display-oriented integration metadata.


## Fields

| Field                              | Type                               | Required                           | Description                        |
| ---------------------------------- | ---------------------------------- | ---------------------------------- | ---------------------------------- |
| `field`                            | *string*                           | :heavy_check_mark:                 | Field key (non-empty, ≤ 60 chars). |
| `value`                            | *string*                           | :heavy_check_mark:                 | Field value (≤ 500 chars).         |