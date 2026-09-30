# AddTaskExternalLinkV1Request


## Fields

| Field                                                                                | Type                                                                                 | Required                                                                             | Description                                                                          |
| ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ |
| `url`                                                                                | *string*                                                                             | :heavy_check_mark:                                                                   | `http` or `https` URL of the link, at most 2,048 characters and without credentials. |
| `title`                                                                              | *?string*                                                                            | :heavy_minus_sign:                                                                   | Optional title of the link, at most 200 characters.                                  |