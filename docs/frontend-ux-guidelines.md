---
tags:
  - frontend
  - ui
  - interactions
---

# Frontend Integration Notes

## Keep host base generic

Do not add game-specific logic to host base module.
All game-specific rendering and interaction belong to your game module.

## Module contract

Your module should:

- export `gameType`
- implement `renderBoard(context)`
- implement `bindInteractions(context)`
- call `registerGameModule(module)` through local bridge

## Type sharing and runtime boundaries

Use shared contracts package for types only:

- `@softcreatr/yabfs-games-types`

Keep runtime bridges local:

- local `Registry.ts` bridge to host registry AMD module
- local menu bridge files for optional quick actions

## Interaction behavior

Use non-blocking interaction patterns in your own game module:

- avoid blocking `window.alert` for expected user feedback
- prefer in-app notices/dialogs
- disable controls during pending request to prevent double submit
- apply immediate optimistic match updates only after successful response

## Data attributes and DOM safety

- keep `data-*` attributes lowercase
- map `dataset` keys carefully
- sanitize dynamic HTML text with escaping helpers

## Polling awareness

- host base performs list/match polling
- game module should be resilient to stale selection and re-render cycles
