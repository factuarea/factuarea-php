# CancelFaceSubmissionV1Request

Public REST API v1 — POST /v1/companies/{company}/face-submissions/{faceSubmission}/cancel.

The cancellation reason (`reason`) is required: it travels to the FACe
web service alongside the cancellation request (code 4200). Same contract
as the SPA surface (`CancelFaceSubmissionRequest`).


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `reason`           | *string*           | :heavy_check_mark: | N/A                |