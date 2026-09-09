# AutomationRuleScope

Which companies the automation watches: `empresa` (the default, and the scope of every rule created before portfolio automations existed) watches only the events of the company that owns it, while `cartera` is reserved for accounting-firm accounts and watches the events of every client company they manage, delivering the notices to the firm itself. It is fixed when the rule is created and cannot be changed afterwards, so a run stays attributable to the scope it executed under.


## Values

| Name      | Value     |
| --------- | --------- |
| `Empresa` | empresa   |
| `Cartera` | cartera   |