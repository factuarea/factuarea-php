# PrimaryLanguage

Language resolved from `Accept-Language` (`es`, `en` or `ca`; `es` when the header is absent or names no supported language). It declares the primary language of the document; it does not filter it — every entry always carries all three translations. It travels inside the body on purpose, which is why two languages yield different `ETag`s.


## Values

| Name  | Value |
| ----- | ----- |
| `Es`  | es    |
| `En`  | en    |
| `Ca`  | ca    |