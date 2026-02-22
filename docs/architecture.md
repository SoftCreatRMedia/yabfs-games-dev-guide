---
tags:
  - architecture
  - provider
  - runtime
---

# Architecture

## Host vs extension

The YABFS game framework is host-driven:

- host framework provides registry, API controllers, service orchestration, pages, templates, polling
- your extension provides game-specific provider metadata, runtime logic, frontend module, and language injection

## Core layers

## 1) Provider registration layer

Interface:

- `files/lib/system/yabfs/game/IYabfsGameProvider.class.php`

Registered by object type under definition:

- `de.softcreatr.wsc.yabfs.game`

Provider responsibilities:

- canonical game type token
- runtime class reference
- language item keys
- frontend AMD module name
- template names

## 2) Runtime layer

Interface:

- `files/lib/system/yabfs/game/runtime/IYabfsGameRuntime.class.php`

Runtime responsibilities:

- invite and match lifecycle behavior
- move validation and turn control
- payload generation
- persistence in `wcf1_yabfs_game_match` and optional `wcf1_yabfs_game_move`

## 3) Registry and service layer

Host classes:

- `files/lib/system/yabfs/game/GameRegistry.class.php`
- `files/lib/system/yabfs/game/GameService.class.php`

Notes:

- service enforces framework-level guards (user/game availability, locks, transaction boundaries)
- service dispatches to runtime selected by `gameType`

## 4) Endpoint layer

Host controllers:

- `files/lib/system/endpoint/controller/yabfs/game/*.class.php`

All standard flow endpoints are generic and route by `{gameType}`.

## 5) Frontend layer

- Core entry: `ts/SoftCreatR/Yabfs/Ui/Page/Games/Base.ts`
- Per-game module: `ts/SoftCreatR/Yabfs/Ui/Page/Games/<YourGame>.ts`
- Registry:
  - host registry: `ts/SoftCreatR/Yabfs/Ui/Page/Games/Registry.ts`
  - extension-side bridge file forwards module registration to the host registry

## 6) Template and language layer

- Core templates:
  - `templates/games.tpl`
  - `templates/gamesPlay.tpl`
  - `templates/__yabfsGameCatalogCardBase.tpl`
  - `templates/__yabfsGamePlayBoardBase.tpl`
- Language injection event in play page:
  - `yabfsGamePlayLanguageItems`

Your extension injects a game phrase template via `templateListener.xml`.

## Optional framework extension points

Base package events usable by add-ons:

- template `__yabfsFriendList` event `yabfsFriendListQuickActions`
- template `__yabfsProfileFriendButton` event `yabfsProfileFriendButtons`
- endpoint class `MenuFriendship` action events `decorateItems` and `beforeResponse`
