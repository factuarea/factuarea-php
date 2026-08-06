# FindRecordByAeatCsvV1Request

Look up a VeriFactu record by its AEAT CSV. Send `{ "aeat_csv": "..." }`; the value is normalized (hyphens removed, upper-cased) before matching. Returns 404 when no record matches.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `aeatCsv`          | *?string*          | :heavy_minus_sign: | N/A                |