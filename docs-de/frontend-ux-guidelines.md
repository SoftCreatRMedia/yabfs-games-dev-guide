---
tags:
  - frontend
  - ui
  - interactions
---

# Hinweise zur Frontend-Integration

## Host-Basis generisch halten

Fügen Sie keine spiel-spezifische Logik in das Host-Basis-Modul ein.
Alle spiel-spezifischen Render- und Interaktionsfunktionen gehören in Ihr Spielmodul.

## Modulvertrag

Ihr Modul sollte:

- `gameType` exportieren
- `renderBoard(context)` implementieren
- `bindInteractions(context)` implementieren
- `registerGameModule(module)` über die lokale Bridge aufrufen

## Typ-Sharing und Laufzeitgrenzen

Verwenden Sie das gemeinsame Contracts-Paket ausschließlich für Typen:

- `@softcreatr/yabfs-games-types`

Halten Sie Laufzeit-Brücken lokal:

- lokale `Registry.ts`-Bridge zum Host-Registry-AMD-Modul
- lokale Menü-Bridge-Dateien für optionale Schnellaktionen

## Interaktionsverhalten

Verwenden Sie nicht-blockierende Interaktionsmuster in Ihrem eigenen Spielmodul:

- vermeiden Sie blockierende `window.alert`-Aufrufe für erwartetes Nutzerfeedback
- bevorzugen Sie In-App-Benachrichtigungen/Dialoge
- deaktivieren Sie Steuerungen während ausstehender Anfragen, um Doppelsendungen zu verhindern
- wenden Sie sofortige optimistische Spielstandupdates nur nach erfolgreicher Antwort an

## Datenattribute und DOM-Sicherheit

- halten Sie `data-*`-Attribute klein geschrieben
- ordnen Sie `dataset`-Schlüssel sorgfältig zu
- bereinigen Sie dynamischen HTML-Text mit Escape-Hilfsmitteln

## Polling-Bewusstsein

- Die Host-Basis führt Listen-/Spiel-Polling durch
- Das Spielmodul sollte gegenüber veralteten Auswahlen und Re-Render-Zyklen widerstandsfähig sein
