---
tags:
  - troubleshooting
  - debugging
  - fixes
---

# Troubleshooting Playbook

Verwenden Sie diese Seite, um häufige Integrationsfehler beim Hinzufügen benutzerdefinierter Spiele zu diagnostizieren.

## Spielkarte erscheint nicht im Katalog

Symptome:

- Spiel ist installiert, aber in der Spieleübersicht nicht vorhanden

Prüfungen:

- Provider-Objekttyp existiert in `objectType.xml`
- `definitionname` ist `de.softcreatr.wsc.yabfs.game`
- Namespace/Pfad der Provider-Klasse stimmt mit Datei überein

Lösung:

- Objekttyp-Eintrag korrigieren, Paket neu erstellen, Cache leeren

## JS-Sprachphrase fehlt zur Laufzeit

Symptome:

- `Language.getPhrase(...)` gibt Schlüssel statt Übersetzung zurück

Prüfungen:

- `templateListener.xml` enthält `gamesPlay`-Event `yabfsGamePlayLanguageItems`
- Name der Sprachvorlagendatei stimmt mit Rückgabewert des Providers (`getPlayLanguageTemplateName`) überein
- Spracheintrag existiert in XML und Paket wurde neu gebaut

Lösung:

- Listener-/Vorlagennamens-Mismatch beheben und Paket neu installieren/aktualisieren

## Spielmodul wird auf der Spielfeldseite nicht geladen

Symptome:

- Grundseite lädt, aber Spielbrett bleibt leer

Prüfungen:

- Provider `getFrontendModuleName()` entspricht kompiliertem AMD-Modulnamen
- TypeScript-Ausgabe liegt im Paket `files/js/...` vor
- Modul ruft `registerGameModule(...)` auf

Lösung:

- Modulnamen-Mismatch beheben und TypeScript-Artefakte neu erstellen

## Invite- oder Move-Endpunkt gibt Validierungs- oder Berechtigungsfehler zurück

Symptome:

- `400`/`403` oder Move wird still abgelehnt

Prüfungen:

- Aktueller Benutzer ist Teilnehmer des Matches
- Match befindet sich im erwarteten Zustand vor der Aktion
- Laufzeit-Payload-Validierung umfasst alle erforderlichen Felder

Lösung:

- Explizite Laufzeitvalidierung einfügen und klare Fehlermeldungen zurückgeben (`UserInputException`, Berechtigungsprüfungen)

## Akzeptieren/Ablehnen/Abbrechen-Buttons fehlen oder sind falsch

Symptome:

- Aktionsbuttons werden nicht wie erwartet dargestellt

Prüfungen:

- Rückgelieferte Match-Payload enthält generische Flags:
  - `canAcceptInvite`
  - `canDeclineInvite`
  - `canCancelInvite`
  - `canMove`

Lösung:

- Generische Flags immer in `toMatchPayload(...)` berechnen und zurückgeben

## Polling-Updates spiegeln nicht den neuesten Zug wider

Symptome:

- Zug gelingt, aber zweiter Browser/Benutzer bleibt veraltet

Prüfungen:

- `lastActionTime` wird bei jeder zustandsverändernden Aktion aktualisiert
- Listen- und Einzel-Match-Snapshot-Antworten enthalten korrekte `unchanged`-Semantik

Lösung:

- Zeitstempel konsistent aktualisieren und Snapshot-Vergleichslogik prüfen

## TypeScript-Kompilierfehler bei gemeinsamen Contracts

Symptome:

- `Contracts`-/`Registry`-Importe können nicht aufgelöst werden

Prüfungen:

- Paket hängt von `@softcreatr/yabfs-games-types` ab
- Lokale `Contracts.ts` exportiert gemeinsame Typen neu
- Lokale `Registry.ts` vermittelt zum Host-Registry-Modul

Lösung:

- Lokale Bridge-Dateien und Abhängigkeitsdeklaration wiederherstellen
