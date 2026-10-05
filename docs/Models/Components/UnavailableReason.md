# UnavailableReason

Why the scope is not available, or `null` when it is. `module_not_granted` means the company does not have the gestoría module (upgrading the plan grants it); `company_is_managed_child` means the company is not the titular company of its account: the portfolio belongs to the account and its titular represents it, so no plan unlocks it for an additional tax ID.


## Values

| Name                     | Value                    |
| ------------------------ | ------------------------ |
| `ModuleNotGranted`       | module_not_granted       |
| `CompanyIsManagedChild`  | company_is_managed_child |