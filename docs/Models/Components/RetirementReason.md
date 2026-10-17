# RetirementReason

Why the entry was withdrawn. `product_replaced` means the product was replaced by another one with a different base unit (`POST /v1/companies/{company}/products/{product}/base-unit-replacement`): the entry keeps its price as history and, when it was transferred, a new entry for the new product carries the converted price. `null` while the entry is active.


## Values

| Name                   | Value                  |
| ---------------------- | ---------------------- |
| `ProductDeleted`       | product_deleted        |
| `VariantDeleted`       | variant_deleted        |
| `PresentationDeleted`  | presentation_deleted   |
| `ConfigurationDeleted` | configuration_deleted  |
| `OptionGroupDeleted`   | option_group_deleted   |
| `OptionValueDeleted`   | option_value_deleted   |
| `LegacyOrphan`         | legacy_orphan          |
| `ProductReplaced`      | product_replaced       |