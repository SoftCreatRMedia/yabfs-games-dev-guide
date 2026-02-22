---
tags:
  - packaging
  - structure
  - extension
---

# Minimaler Paketbaum

Minimales Erweiterungspaket-Layout für ein Spiel.

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

## Obligatorische Paket-Verkabelung

`package.xml` sollte erforderliche Anweisungen für enthalten:

- file
- template
- language
- templateListener
- objectType

Sowie erforderliche Abhängigkeiten:

- WCF 6.2+
- Host YABFS Games Framework Paket
