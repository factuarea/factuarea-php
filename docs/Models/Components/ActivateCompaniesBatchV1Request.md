# ActivateCompaniesBatchV1Request

Reactivate several managed companies in a single request. `company_ids` is a list of company IDs (UUID v7) to reactivate, between 1 and 1000. Ownership of each company and its required `inactive` status are enforced server-side; companies that are not yours or not inactive are reported per item without affecting the rest.


## Fields

| Field                                                                                   | Type                                                                                    | Required                                                                                | Description                                                                             |
| --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- |
| `companyIds`                                                                            | array<*string*>                                                                         | :heavy_check_mark:                                                                      | List of managed child company IDs (UUID v7) to reactivate in bulk (between 1 and 1000). |