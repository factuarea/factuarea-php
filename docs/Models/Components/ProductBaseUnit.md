# ProductBaseUnit

UNECE base unit code. Only the nine measure units can be a base unit (`C62`, `KGM`, `GRM`, `LTR`, `MLT`, `MTR`, `MTK`, `HUR`, `DAY`); a packaging or count unit (`H87`, `XPK`, `XBX`…) can only be the unit of a presentation or a supplier offer. It cannot be edited once the product has history: see `base_unit_change` and `POST /v1/companies/{company}/products/{product}/base-unit-replacement`.


## Values

| Name  | Value |
| ----- | ----- |
| `C62` | C62   |
| `Kgm` | KGM   |
| `Grm` | GRM   |
| `Ltr` | LTR   |
| `Mlt` | MLT   |
| `Mtr` | MTR   |
| `Mtk` | MTK   |
| `Hur` | HUR   |
| `Day` | DAY   |