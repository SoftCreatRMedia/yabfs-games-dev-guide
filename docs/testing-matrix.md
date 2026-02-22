---
tags:
  - testing
  - qa
  - verification
---

# Testing Matrix

Minimum regression matrix for every new game extension.

| Area | Scenario | Expected |
|---|---|---|
| Invite | valid invite between eligible users | invited match created |
| Invite | self invite | rejected |
| Invite | duplicate open invite | existing match reused or duplicate blocked |
| Accept | invitee accepts invited match | state becomes active |
| Accept | inviter tries to accept | rejected |
| Decline | invitee declines invited match | state becomes declined |
| Cancel | inviter cancels invited match | state becomes cancelled |
| Move | valid move on active turn | board and turn update |
| Move | wrong-turn user moves | rejected |
| Move | illegal payload (out of range) | rejected |
| Finish | win/draw condition reached | state finished and result set |
| Forfeit | participant forfeits active match | state finished, winner set to opponent |
| Polling | list with `since` no changes | unchanged response |
| Polling | list with `since` after move | changed snapshot response |
| Security | actor not participant accesses match | rejected |
| Language | JS phrases injected | no missing phrase in UI |
| Catalog | provider object type registered | game visible in catalog |

## Recommended automation split

- backend integration tests for runtime transitions
- API tests for endpoint payload and permission behavior
- frontend smoke tests for render and action wiring
