# CreateReturnRequest

Request the return of one or more lines of a delivery note, an invoice or a
sales order.

The return is born in the requested state, which is the only state where it
can still be edited or deleted. Asking for it consumes no numbering and moves
no stock: goods come back when the return is received, and money goes back
when it is refunded.

## The origin

 - `origin_type` (required) — where the return comes from: `delivery_note`,
   `invoice` or `sales_order`, and nothing else. The value travels as written:
   `Invoice` or a value padded with blanks is rejected instead of being
   silently fixed, so the same origin is never stored under two spellings.
 - `origin_id` (required) — public identifier of that document, which has to
   belong to the company in the path.

The origin cannot be changed afterwards: returning against another document
means rejecting this return, which frees the quantity, and asking for a new
one.

## The lines

`lines` is required and needs at least one entry. Each entry carries:

 - `origin_line_id` (required) — which line of the origin document comes back.
   It is the reference handed out by the returnable lines reading of that same
   document, and it is bounded by the origin: the return is checked again
   against that document within that company before anything is inherited from
   it.
 - `quantity` (required) — how much comes back, greater than zero, with up to
   ten integer digits and four decimals. It travels as a decimal number and is
   compared as a decimal: in floating point, four decimals lose scale before
   anyone compares them against what was delivered.
 - `reason` (required) — why that line comes back, out of the closed set of
   four values.

## What this body does not accept, stated so it does not read as an oversight

 - **Unit price and tax rate of a line.** Both are inherited from the origin
   line. The amount to be corrected is not decided by whoever asks for the
   return: it is decided by the document being returned, and the refund step
   issues a corrective invoice that cannot be deleted afterwards, only voided
   with another fiscal document. Both keys are dropped from the validated body
   and never reach the created line.
 - **Product and variant of a line.** Inherited from the origin line too, for
   the same reason, so there is no product reference in this body.
 - **Request date.** The return window is measured between the reference date
   of the origin and the moment of asking, so letting the caller choose that
   moment would let the caller choose whether the window has expired.
 - **Series, number and state.** The provisional number is stamped when the
   return is requested and the definitive one when it is approved, out of the
   live return series of the company. A return is always born requested and
   every step forward has an operation of its own.
 - **Target invoice.** The invoice to be corrected is resolved from the origin
   when the refund happens, and can be fixed beforehand through the partial
   update.
 - **Company and actor.** Both come from the path and from the credential.

## Optional fields and how to leave them out

 - `reason` — header reason out of the same closed set of four values. Left
   out, it is derived from the lines when they all share one.
 - `warehouse_id` — public identifier of the warehouse that will take the
   goods in, active and of the company in the path.
 - `notes` — free text, up to 1000 characters.

An optional text field left blank is rejected rather than read as absence:
omit the field instead of sending empty text, so the contract never depends on
whether someone typed a space.

## Company

Every reference in this body is checked against the company in the path, so a
document or a warehouse of another company is rejected exactly like one that
does not exist, without revealing that it exists.

## What comes back

A created return with its public identifier, its requested state and its lines
carrying the price and the tax rate inherited from the origin, plus the
location header of the new resource.


## Fields

| Field                                                                                                                                                                                                                                                                                  | Type                                                                                                                                                                                                                                                                                   | Required                                                                                                                                                                                                                                                                               | Description                                                                                                                                                                                                                                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `originType`                                                                                                                                                                                                                                                                           | [Components\CreateReturnRequestOriginType](../../Models/Components/CreateReturnRequestOriginType.md)                                                                                                                                                                                   | :heavy_check_mark:                                                                                                                                                                                                                                                                     | Kind of document the goods came out on: `delivery_note`, `invoice` or `sales_order`. It says where the returnable quantities are read from, and it is not redundant with the identifier: the three kinds are numbered independently.                                                   |
| `originId`                                                                                                                                                                                                                                                                             | *string*                                                                                                                                                                                                                                                                               | :heavy_check_mark:                                                                                                                                                                                                                                                                     | Public ID of the origin document of that kind, of the company in the path. A document of another company is rejected exactly like one that does not exist.                                                                                                                             |
| `reason`                                                                                                                                                                                                                                                                               | [?Components\CreateReturnRequestReason](../../Models/Components/CreateReturnRequestReason.md)                                                                                                                                                                                          | :heavy_minus_sign:                                                                                                                                                                                                                                                                     | Header reason of the return; left out, it is derived from the lines                                                                                                                                                                                                                    |
| `warehouseId`                                                                                                                                                                                                                                                                          | *?string*                                                                                                                                                                                                                                                                              | :heavy_minus_sign:                                                                                                                                                                                                                                                                     | Public ID of the warehouse that takes the goods back in: an ACTIVE warehouse of the company in the path. Omitting it leaves the destination to be decided when the goods are received. An archived one is rejected, because goods cannot come back into a site that is out of service. |
| `notes`                                                                                                                                                                                                                                                                                | *?string*                                                                                                                                                                                                                                                                              | :heavy_minus_sign:                                                                                                                                                                                                                                                                     | Free text of the return, up to 1000 characters.                                                                                                                                                                                                                                        |
| `lines`                                                                                                                                                                                                                                                                                | array<[Components\CreateReturnRequestLine](../../Models/Components/CreateReturnRequestLine.md)>                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                     | Lines that come back: at least one. Each entry names the origin line, how much of it comes back and why. Neither the unit price nor the tax rate travels here: both are inherited from the origin document, so a return can never restate the amount of what it gives back.            |