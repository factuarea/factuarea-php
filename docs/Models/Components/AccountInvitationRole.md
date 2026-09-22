# AccountInvitationRole

Membership role the person would get. Only `admin` and `member` can be invited through this surface; `employee` appears on invitations created by the Control Horario flow, which owns its own seat. `owner` never appears: NIF ownership is not handed out by invitation.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Admin`    | admin      |
| `Member`   | member     |
| `Employee` | employee   |