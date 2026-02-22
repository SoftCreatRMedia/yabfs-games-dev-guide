---
tags:
  - starter-kit
  - tooling
  - scaffold
---

# Starter Kit Reference

This repository ships a cloneable starter package scaffold under:

`starter-kit/yabfs-game-starter`

GitHub tree:

`https://github.com/SoftCreatRMedia/yabfs-games-dev-guide/tree/main/starter-kit/yabfs-game-starter`

## What is included

- `package.xml` with baseline package wiring and dependencies
- `objectType.xml` provider registration template
- `templateListener.xml` + JS language template
- `language/*.xml` for `en, de, cs, da, es, fr, hu, it, nl, no, pl, ro, ru, sv, tr`
- provider/runtime PHP classes with a working minimal game flow
- TypeScript module + registry bridge + full lint/build toolchain config
- dev dependencies aligned with YABFS game packages (`TypeScript`, `ESLint`, `Prettier`, `esbuild`, shared types package)

## Quick start

1. Copy `starter-kit/yabfs-game-starter` into your own extension repository.
2. Rename package/class/language identifiers from `exampleGame` to your game type.
3. Run `npm i && npm run build` to compile the frontend module.
4. Install package in your WoltLab dev instance.
5. Replace the example move logic with your own game logic.

## Included tree

```text
starter-kit/yabfs-game-starter/
├─ package.xml
├─ objectType.xml
├─ templateListener.xml
├─ language/
│  ├─ en.xml
│  ├─ de.xml
│  └─ ... (cs, da, es, fr, hu, it, nl, no, pl, ro, ru, sv, tr)
├─ files/
│  └─ lib/system/yabfs/game/
│     ├─ provider/ExampleGameProvider.class.php
│     └─ runtime/ExampleGameRuntime.class.php
├─ templates/
│  └─ __yabfsGamePlayLanguageExampleGame.tpl
└─ ts/
   └─ SoftCreatR/Yabfs/Ui/Page/Games/
      ├─ Contracts.ts
      ├─ Registry.ts
      └─ ExampleGame.ts
```

This starter is intentionally small but fully working, so it can be used directly as a base package.
