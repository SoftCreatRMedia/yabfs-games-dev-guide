---
tags:
  - runtime
  - state-machine
  - backend
---

# Runtime State Machine

State constants (`GameMatch`):

- `0` invited (`STATE_INVITED`)
- `1` active (`STATE_ACTIVE`)
- `2` finished (`STATE_FINISHED`)
- `3` declined (`STATE_DECLINED`)
- `4` cancelled (`STATE_CANCELLED`)

## Transition table

| From | Action | Actor | Guard | To |
|---|---|---|---|---|
| none | invite | sender | users are eligible and allowed | invited |
| invited | acceptInvite | recipient | recipient is not inviter | active |
| invited | declineInvite | recipient | recipient is not inviter | declined |
| invited | cancelInvite | inviter | actor is inviter | cancelled |
| active | makeMove | current turn user | legal move payload | active or finished |
| active | forfeit | participant | actor is participant | finished |
| invited/active | disable | system | game disabled | cancelled |

## Required guards per runtime action

`invite`:

- no self-invite
- target user exists
- actor and target can receive game invitations
- framework-level friendship and module constraints are met
- prevent duplicate open match for same pair and game type

`acceptInvite`, `declineInvite`, `cancelInvite`:

- match exists
- actor is participant
- match belongs to runtime `gameType`
- state is invited
- role guard (inviter vs invitee) is correct

`makeMove`:

- match exists
- actor is participant
- match belongs to runtime `gameType`
- state is active
- actor is current turn user
- payload is structurally valid
- move is legal according to game board/state

`disable`:

- close invited/active matches for runtime game type
- clean related notifications if used

## Idempotency guidance

Recommended behavior:

- if a repeated request matches already-applied state, return current payload instead of throwing where possible
- keep strict throws for truly invalid or unauthorized transitions
