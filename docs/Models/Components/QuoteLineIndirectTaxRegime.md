# QuoteLineIndirectTaxRegime

Indirect tax regime of the line: the per-document override (`iva`/`igic`/`ipsi`) when the user set it (precedence override>zone), otherwise `null` (derived from the establishment AEAT zone). Writable per-document input on create/update; the document must be homogeneous (a single non-null regime across all lines, 422 otherwise).


## Values

| Name   | Value  |
| ------ | ------ |
| `Iva`  | iva    |
| `Igic` | igic   |
| `Ipsi` | ipsi   |