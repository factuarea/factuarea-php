# ChangeManagedAccountPlanV1Request

Change the plan of the managed account of the path, which your account pays: `plan` is `emprendedor` or `empresario` (`enterprise` is only assigned by Factuarea by contract). An upgrade is charged at once; a downgrade takes effect at the end of the period.


## Fields

| Field                                                                                                                | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `plan`                                                                                                               | [Components\ChangeManagedAccountPlanV1RequestPlan](../../Models/Components/ChangeManagedAccountPlanV1RequestPlan.md) | :heavy_check_mark:                                                                                                   | Requested plan: `emprendedor` or `empresario`. `enterprise` returns 422 `managed_account_plan_not_self_serve`.       |