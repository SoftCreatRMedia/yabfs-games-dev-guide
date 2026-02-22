---
tags:
  - packaging
  - structure
  - extension
---

# Minimal Package Tree

Minimal extension package layout for one game.

```text
vendor.yabfs.games.mygame/
├─ package.xml
├─ objectType.xml
├─ templateListener.xml
├─ language/
│  ├─ en.xml
│  └─ de.xml
├─ files/
│  ├─ lib/
│  │  └─ system/
│  │     └─ yabfs/
│  │        └─ game/
│  │           ├─ provider/
│  │           │  └─ MyGameProvider.class.php
│  │           └─ runtime/
│  │              └─ MyGameRuntime.class.php
│  └─ images/
│     └─ yabfs/
│        └─ games/
│           └─ mygame.webp
├─ templates/
│  └─ __yabfsGamePlayLanguageMyGame.tpl
└─ ts/
   └─ SoftCreatR/
      └─ Yabfs/
         └─ Ui/
            └─ Page/
               └─ Games/
                  ├─ Contracts.ts
                  ├─ Registry.ts
                  └─ MyGame.ts
```

## Mandatory package wiring

`package.xml` should include required instructions for:

- file
- template
- language
- templateListener
- objectType

Plus required dependencies:

- WCF 6.2+
- host YABFS games framework package
