# PdfStatus

`ready`: the PDF is already in the private cache and `url` serves it at once (only with `options.wait_for_pdf`, which enqueues the render and waits up to about 15 s). `pending`: the render was enqueued and `url` answers 404 until the worker materializes it (seconds) — also the state of a `wait_for_pdf` whose wait expired. It is never an error.


## Values

| Name      | Value     |
| --------- | --------- |
| `Ready`   | ready     |
| `Pending` | pending   |