# AddShipmentPackageRequest

Adds one package to the shipment of a delivery note.

## A package with nothing filled in is a legitimate package

All six properties are optional and none of them is required: a warehouse
counts boxes before weighing and measuring them, and demanding a weight would
turn "there are three boxes" into a form that cannot be sent. Not weighed is
NOT weighing zero, and not measured is NOT measuring zero by zero by zero, so
there are no default values either: what you leave out stays empty.

## Weight and the three sides

`weight_kg` is in kilograms and `length_cm`, `width_cm` and `height_cm` are in
centimetres. The four of them accept at most eight whole digits and two
decimals, which is exactly what their storage holds. A value above that
ceiling, or one carrying a third decimal, is rejected as a validation error
naming the property at fault, and it is rejected BEFORE the shipment is
touched: the alternative is the write being refused further down and the
request answering with a server error that names nothing.

Send the four of them as numeric STRINGS, which is also how they are read
back. Two decimals do not survive a binary floating point number, and the
weight that was declared would stop being the weight somebody typed.

Zero is accepted and a negative value is not. A box that weighs nothing makes
no physical sense, but a zero typed by mistake is still what the user wrote,
and rejecting it honestly for being negative is more useful than rejecting it
for having to weigh something.

## The position is not sent

The position of the package inside the shipment is assigned as the next one of
the delivery note, so that two packages added at the same time from two places
cannot claim the same position. It is what a warehouse reads out loud ("box 2
 * of 3") and never an identifier.

## The two text properties

`reference` is the label the carrier prints for THIS package, up to 60
characters, and it is the same kind of value as the reference of the shipment
as a whole. `notes` is free internal text.


## Fields

| Field                                                                                                                                                                                         | Type                                                                                                                                                                                          | Required                                                                                                                                                                                      | Description                                                                                                                                                                                   |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `reference`                                                                                                                                                                                   | *?string*                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                            | Label the carrier prints for THIS package, up to 60 characters. It is the number on the sticker, not the reference of the whole shipment, which belongs to the shipment itself.               |
| `weightKg`                                                                                                                                                                                    | *?float*                                                                                                                                                                                      | :heavy_minus_sign:                                                                                                                                                                            | Weight of the package in kilograms, with at most two decimals and up to 99999999.99. The total weight of the shipment is the sum of its packages and is calculated for you: it is never sent. |
| `lengthCm`                                                                                                                                                                                    | *?float*                                                                                                                                                                                      | :heavy_minus_sign:                                                                                                                                                                            | Length of the package in centimetres, with at most two decimals and up to 99999999.99.                                                                                                        |
| `widthCm`                                                                                                                                                                                     | *?float*                                                                                                                                                                                      | :heavy_minus_sign:                                                                                                                                                                            | Width of the package in centimetres, with the same limits as the length.                                                                                                                      |
| `heightCm`                                                                                                                                                                                    | *?float*                                                                                                                                                                                      | :heavy_minus_sign:                                                                                                                                                                            | Height of the package in centimetres, with the same limits as the length.                                                                                                                     |
| `notes`                                                                                                                                                                                       | *?string*                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                            | Free text about the package, for whoever handles it. It is not printed on the label and does not reach the carrier.                                                                           |