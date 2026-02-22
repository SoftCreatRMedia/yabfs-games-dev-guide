---
tags:
  - starter-kit
  - tooling
  - scaffold
---

# Starter-Kit Referenz

Dieses Repository enthält ein klonbares Starter-Paketgerüst unter:

`starter-kit/yabfs-game-starter`

GitHub-Verzeichnis:

`https://github.com/SoftCreatRMedia/yabfs-games-dev-guide/tree/main/starter-kit/yabfs-game-starter`

## Was enthalten ist

- `package.xml` mit Grundverkabelung des Pakets und Abhängigkeiten
- `objectType.xml` Vorlage zur Provider-Registrierung
- `templateListener.xml` + JS-Sprachtemplate
- `language/*.xml` für `en, de, cs, da, es, fr, hu, it, nl, no, pl, ro, ru, sv, tr`
- Provider-/Runtime-PHP-Klassen mit funktionierendem minimalem Spielablauf
- TypeScript-Modul + Registry-Bridge + vollständige Lint-/Build-Toolchain-Konfiguration
- Entwicklungsabhängigkeiten abgestimmt auf YABFS-Spielpakete (`TypeScript`, `ESLint`, `Prettier`, `esbuild`, gemeinsames Typenpaket)

## Schnellstart

1. Kopieren Sie `starter-kit/yabfs-game-starter` in Ihr eigenes Erweiterungs-Repository.
2. Benennen Sie Paket-/Klassen-/Sprachenkennungen von `exampleGame` in Ihren Spieltyp um.
3. Führen Sie `npm i && npm run build` aus, um das Frontend-Modul zu kompilieren.
4. Installieren Sie das Paket in Ihrer WoltLab-Dev-Instanz.
5. Ersetzen Sie die Beispielslogik für Züge durch Ihre eigene Spiellogik.

## Enthaltener Verzeichnisbaum

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

Dieses Starter-Kit ist bewusst klein, aber voll funktionsfähig, sodass es direkt als Basispaket verwendet werden kann.
