# ErrorType

Machine-readable category of the error. Versioned by `Factuarea-Version`: from `2026-09-01` onwards the payment gate codes `payment_method_required`, `seat_charge_failed`, `gestoria_plan_required`, `employee_seat_payment_method_required` and `employee_seat_charge_failed` are served as `payment_required_error`; requests pinned to an earlier version keep receiving `invalid_request_error` for those five. `error.code` and `error.subcode` never change — branch on `code` if you want version-independent behaviour.


## Values

| Name                      | Value                     |
| ------------------------- | ------------------------- |
| `ApiError`                | api_error                 |
| `AuthenticationError`     | authentication_error      |
| `AuthorizationError`      | authorization_error       |
| `ConflictError`           | conflict_error            |
| `IdempotencyError`        | idempotency_error         |
| `InvalidRequestError`     | invalid_request_error     |
| `NotFoundError`           | not_found_error           |
| `PaymentRequiredError`    | payment_required_error    |
| `PermissionError`         | permission_error          |
| `RateLimitError`          | rate_limit_error          |
| `ServiceUnavailableError` | service_unavailable_error |