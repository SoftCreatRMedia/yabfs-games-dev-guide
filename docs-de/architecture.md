---
tags:
  - architecture
  - provider
  - runtime
---

# Architektur

## Host vs. Erweiterung

Das YABFS-Spiel-Framework ist host-gesteuert:

- Das Host-Framework stellt Registry, API-Controller, Service-Orchestrierung, Seiten, Templates, Polling bereit
- Ihre Erweiterung liefert spiel-spezifische Provider-Metadaten, Laufzeitlogik, Frontend-Modul und Spracheinbindung

## Kernschichten

## 1) Provider-Registrierungsschicht

Schnittstelle:

- `files/lib/system/yabfs/game/IYabfsGameProvider.class.php`

Registriert nach Objekttyp unter Definition:

- `de.softcreatr.wsc.yabfs.game`

Provider-Verantwortlichkeiten:

- kanonischer Spieltyp-Token
- Laufzeitklassen-Referenz
- Sprach-Item-Schlüssel
- Frontend-AMD-Modulname
- Template-Namen

## 2) Laufzeitschicht

Schnittstelle:

- `files/lib/system/yabfs/game/runtime/IYabfsGameRuntime.class.php`

Laufzeit-Verantwortlichkeiten:

- Ablaufverhalten für Einladungen und Matches
- Zugvalidierung und Zugsteuerung
- Payload-Erzeugung
- Persistenz in `wcf1_yabfs_game_match` und optional `wcf1_yabfs_game_move`

## 3) Registry- und Serviceschicht

Host-Klassen:

- `files/lib/system/yabfs/game/GameRegistry.class.php`
- `files/lib/system/yabfs/game/GameService.class.php`

Hinweise:

- Der Service setzt Framework-weite Einschränkungen durch (Benutzer-/Spielverfügbarkeit, Sperren, Transaktionsgrenzen)
- Der Service leitet an Laufzeit weiter, die durch `gameType` ausgewählt wird

## 4) Endpunktschicht

Host-Controller:

- `files/lib/system/endpoint/controller/yabfs/game/*.class.php`

Alle Standard-Flow-Endpunkte sind generisch und leiten über `{gameType}`.

## 5) Frontendschicht

- Kern-Einstiegspunkt: `ts/SoftCreatR/Yabfs/Ui/Page/Games/Base.ts`
- Pro-Spiel-Modul: `ts/SoftCreatR/Yabfs/Ui/Page/Games/<YourGame>.ts`
- Registry:
  - Host-Registry: `ts/SoftCreatR/Yabfs/Ui/Page/Games/Registry.ts`
  - Erweiterungsseitige Bridge-Datei leitet Modulregistrierung an Host-Registry weiter

## 6) Template- und Sprachschicht

- Kern-Templates:
  - `templates/games.tpl`
  - `templates/gamesPlay.tpl`
  - `templates/__yabfsGameCatalogCardBase.tpl`
  - `templates/__yabfsGamePlayBoardBase.tpl`
- Spracheinbindungs-Event in der Spielseite:
  - `yabfsGamePlayLanguageItems`

Ihre Erweiterung injiziert ein Spielphrase-Template über `templateListener.xml`.

## Optionale Framework-Erweiterungspunkte

Basis-Paket-Events nutzbar für Add-ons:

- Template `__yabfsFriendList` Event `yabfsFriendListQuickActions`
- Template `__yabfsProfileFriendButton` Event `yabfsProfileFriendButtons`
- Endpunktklasse `MenuFriendship` Action-Events `decorateItems` und `beforeResponse`
