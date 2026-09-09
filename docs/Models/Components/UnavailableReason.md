# UnavailableReason

Why the scope is not available, or `null` when it is. `module_not_granted` means the company does not have the accountancy-practice module (upgrading the plan grants it); `company_is_managed_child` means the company is itself managed by an accountancy practice, and the hierarchy is one level deep, so no plan unlocks it.


## Values

| Name                     | Value                    |
| ------------------------ | ------------------------ |
| `ModuleNotGranted`       | module_not_granted       |
| `CompanyIsManagedChild`  | company_is_managed_child |