# PackGroupPrintMode

How the group is printed in the PDF. New groups are always `full_breakdown`: every sub-line prints as a normal line with its amounts and the name of its pack under it. `pack_with_contents` and `contents_only` only appear in groups saved before every group printed the same way, and they print exactly like `full_breakdown`.


## Values

| Name               | Value              |
| ------------------ | ------------------ |
| `FullBreakdown`    | full_breakdown     |
| `PackWithContents` | pack_with_contents |
| `ContentsOnly`     | contents_only      |