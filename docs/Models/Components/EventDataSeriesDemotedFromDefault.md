# EventDataSeriesDemotedFromDefault

Payload (`data`) emitted with the `series.demoted_from_default` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                       | Type                                                        | Required                                                    | Description                                                 |
| ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- |
| `type`                                                      | *string*                                                    | :heavy_check_mark:                                          | N/A                                                         |
| `object`                                                    | [Components\Series](../../Models/Components/Series.md)      | :heavy_check_mark:                                          | A document numbering series. Immutable per AEAT compliance. |