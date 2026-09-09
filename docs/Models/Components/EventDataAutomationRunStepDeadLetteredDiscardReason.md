# EventDataAutomationRunStepDeadLetteredDiscardReason

Typed reason the step was parked, from a CLOSED catalogue — never free-form text. In practice it is always a replayable reason: an outcome whose reason does not admit reprocessing closes the run as `failed` instead of parking the step.


## Values

| Name                                 | Value                                |
| ------------------------------------ | ------------------------------------ |
| `ConditionNotMatched`                | condition_not_matched                |
| `ConditionNotEvaluableForEvent`      | condition_not_evaluable_for_event    |
| `ConditionDefinitionInvalid`         | condition_definition_invalid         |
| `ChainDepthExceeded`                 | chain_depth_exceeded                 |
| `RateLimitExceeded`                  | rate_limit_exceeded                  |
| `MonthlyBudgetExhausted`             | monthly_budget_exhausted             |
| `TenantMismatch`                     | tenant_mismatch                      |
| `SandboxNeutralized`                 | sandbox_neutralized                  |
| `ActionTypeUnregistered`             | action_type_unregistered             |
| `ActionParametersInvalid`            | action_parameters_invalid            |
| `ChannelIntegrationInactive`         | channel_integration_inactive         |
| `DocumentTargetNotFound`             | document_target_not_found            |
| `DocumentRecipientMissing`           | document_recipient_missing           |
| `DocumentNotSendable`                | document_not_sendable                |
| `ReminderCooldownActive`             | reminder_cooldown_active             |
| `ReminderNotApplicable`              | reminder_not_applicable              |
| `StatusTransitionNotAllowed`         | status_transition_not_allowed        |
| `DocumentTagLimitExceeded`           | document_tag_limit_exceeded          |
| `DocumentCustomFieldLimitExceeded`   | document_custom_field_limit_exceeded |
| `DocumentTypeNotSupported`           | document_type_not_supported          |
| `StepAttemptsExhausted`              | step_attempts_exhausted              |
| `RunExecutionTimeout`                | run_execution_timeout                |