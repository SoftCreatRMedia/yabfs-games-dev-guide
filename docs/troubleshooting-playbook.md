---
tags:
  - troubleshooting
  - debugging
  - fixes
---

# Troubleshooting Playbook

Use this page to diagnose common integration failures when adding custom games.

## Game card does not appear in catalog

Symptoms:

- game is installed but missing in games overview

Checks:

- provider object type exists in `objectType.xml`
- `definitionname` is `de.softcreatr.wsc.yabfs.game`
- provider class namespace/path matches file

Fix:

- correct object type entry, rebuild package, clear cache

## JS language phrase is missing at runtime

Symptoms:

- `Language.getPhrase(...)` returns key instead of translation

Checks:

- `templateListener.xml` includes `gamesPlay` event `yabfsGamePlayLanguageItems`
- language template file name matches provider return value (`getPlayLanguageTemplateName`)
- language item exists in XML and package was rebuilt

Fix:

- fix listener/template naming mismatch and reinstall/update package

## Game module not loaded in play page

Symptoms:

- base page loads but game board stays empty

Checks:

- provider `getFrontendModuleName()` matches compiled AMD module name
- TypeScript output exists in package `files/js/...`
- module calls `registerGameModule(...)`

Fix:

- fix module name mismatch and rebuild TypeScript artifacts

## Invite/move endpoint returns validation or permission errors

Symptoms:

- `400`/`403` or move silently rejected

Checks:

- current user is participant of match
- match is in expected state before action
- runtime payload validation covers all required fields

Fix:

- add explicit runtime validation and clear error responses (`UserInputException`, permission checks)

## Accept/decline/cancel buttons missing or wrong

Symptoms:

- action buttons not rendered as expected

Checks:

- returned match payload includes generic flags:
  - `canAcceptInvite`
  - `canDeclineInvite`
  - `canCancelInvite`
  - `canMove`

Fix:

- always compute and return generic flags in `toMatchPayload(...)`

## Polling updates do not reflect newest move

Symptoms:

- move succeeds but second browser/user remains stale

Checks:

- `lastActionTime` updated on every state-changing action
- list and single-match snapshot responses include proper `unchanged` semantics

Fix:

- update timestamps consistently and verify snapshot comparison logic

## TypeScript compile errors for shared contracts

Symptoms:

- cannot resolve `Contracts`/`Registry` imports

Checks:

- package depends on `@softcreatr/yabfs-games-types`
- local `Contracts.ts` re-exports shared types
- local `Registry.ts` bridges to host registry module

Fix:

- restore local bridge files and dependency declaration
