# StorefrontSelectedOption

One configurable choice already made, normalised as a pair of group and value. The pair is the unit: a loose value would not say which group it answers, and two groups can offer values with the same name.


## Fields

| Field                                                     | Type                                                      | Required                                                  | Description                                               | Example                                                   |
| --------------------------------------------------------- | --------------------------------------------------------- | --------------------------------------------------------- | --------------------------------------------------------- | --------------------------------------------------------- |
| `groupId`                                                 | *string*                                                  | :heavy_check_mark:                                        | Opaque identifier of the option group the choice answers. | 0199f2a3-d101-7f60-9172-d0e1f2a3b431                      |
| `valueId`                                                 | *string*                                                  | :heavy_check_mark:                                        | Opaque identifier of the chosen value inside that group.  | 0199f2a3-e101-7071-8283-d0e1f2a3b441                      |