# StockReservationHolder

Who holds the goods. It is an OPAQUE pair on purpose: the API does not know what a `cart` is in your system and does not validate it against any table of ours.


## Fields

| Field                                                                                                 | Type                                                                                                  | Required                                                                                              | Description                                                                                           |
| ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| `type`                                                                                                | *string*                                                                                              | :heavy_check_mark:                                                                                    | Kind of holder, as the integration declared it when the reservation was created (for example `cart`). |
| `id`                                                                                                  | *string*                                                                                              | :heavy_check_mark:                                                                                    | Identifier of the holder in the system of the integration.                                            |