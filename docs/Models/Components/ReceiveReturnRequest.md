# ReceiveReturnRequest

Receiving the goods of an approved return.

This is the step that puts the merchandise back into the warehouse: the stock
entry is written inside the same transaction that moves the return to
received, so a failure never leaves a received return without goods, nor
goods without a return behind them.

## The destination warehouse is optional, and leaving it out is not "none"

When the body does not name a warehouse, the destination is INHERITED from
the stock ledger: the goods go back to the warehouse they left from. Forcing
a choice here would invite receiving into a warehouse other than the one that
shipped, which leaves the shipping one permanently negative while the global
balance still adds up — a mismatch nobody sees until the physical count.

When the body does name one, it has to be an ACTIVE warehouse of the same
company as the path. An archived warehouse is refused because goods cannot
enter a warehouse that is out of service, and a warehouse held by another
company is refused exactly like one that does not exist, with no way to tell
the two apart.

## The reception date is optional and cannot be in the future

Leaving it out means now. It is accepted because goods can arrive on a Friday
and be registered on Monday, and this date is the one that dates the stock
movement. A future date is refused: a movement dated tomorrow brings forward
goods that are not in the warehouse yet, and every stock report cut at a date
would read them as already there. The date is a plain calendar date,
`YYYY-MM-DD`, with no time part.

## No quantity travels in this body

What is received is what was approved. There is no property for quantities,
neither for the return as a whole nor per line: accepting one would let the
caller put into stock something different from what the approval authorised,
and each returned quantity is already fixed when the return is requested.
A property this body does not declare is ignored, and what enters the ledger
is always the approved quantity.

## Idempotency key

Receiving is declared irreversible — it writes the entry of goods into the
ledger and no operation of this API reverses it — so this operation asks for
an idempotency key header whenever the policy of the credential in use
requires one. Send a unique key per operation and repeat exactly the same key
when you retry, so an answer lost on the wire never turns into a second entry
of the same goods.


## Fields

| Field                                                                                                                                                                                                                                                                                                                                             | Type                                                                                                                                                                                                                                                                                                                                              | Required                                                                                                                                                                                                                                                                                                                                          | Description                                                                                                                                                                                                                                                                                                                                       |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `warehouseId`                                                                                                                                                                                                                                                                                                                                     | *?string*                                                                                                                                                                                                                                                                                                                                         | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                | Public ID of the warehouse the goods physically come back into: an ACTIVE warehouse of the company in the path. Omitting it takes the destination already recorded on the return. A warehouse of another company, an archived one and one that does not exist are all rejected the same way, naming the field, so the three cannot be told apart. |
| `receivedAt`                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                     | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                | Date the goods physically arrived, as a calendar date `YYYY-MM-DD` with no time of day. Omitting it records the moment of the call. A date in the future is rejected: goods that have not arrived cannot have been received. No quantity travels here — receiving takes in what the return declares, in full.                                     |