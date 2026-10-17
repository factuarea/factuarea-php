# AutomationRuleScope

Which tax IDs the automation watches: `empresa` (the default, and the scope of every rule created before portfolio automations existed) watches only the events of the company that owns it, while `cartera` is reserved for the titular company of an account with the gestoría module and watches the events of the other tax IDs the account operates (its own and those another account lets it operate), delivering the notices to the titular itself. It is fixed when the rule is created and cannot be changed afterwards, so a run stays attributable to the scope it executed under.


## Values

| Name      | Value     |
| --------- | --------- |
| `Empresa` | empresa   |
| `Cartera` | cartera   |