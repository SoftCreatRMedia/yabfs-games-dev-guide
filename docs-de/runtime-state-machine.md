---
tags:
  - runtime
  - state-machine
  - backend
---

# Laufzeit-Zustandsmaschine

Zustandskonstanten (`GameMatch`):

- `0` eingeladen (`STATE_INVITED`)
- `1` aktiv (`STATE_ACTIVE`)
- `2` beendet (`STATE_FINISHED`)
- `3` abgelehnt (`STATE_DECLINED`)
- `4` abgebrochen (`STATE_CANCELLED`)

## Übergangstabelle

| Von | Aktion | Akteur | Bedingung | Nach |
|---|---|---|---|---|
| none | invite | Absender | Benutzer sind berechtigt und zugelassen | eingeladen |
| eingeladen | acceptInvite | Empfänger | Empfänger ist nicht Einladender | aktiv |
| eingeladen | declineInvite | Empfänger | Empfänger ist nicht Einladender | abgelehnt |
| eingeladen | cancelInvite | Einladender | Akteur ist Einladender | abgebrochen |
| aktiv | makeMove | aktueller Zug-Benutzer | gültiger Zugs-Payload | aktiv oder beendet |
| aktiv | forfeit | Teilnehmer | Akteur ist Teilnehmer | beendet |
| eingeladen/aktiv | disable | System | Spiel deaktiviert | abgebrochen |

## Erforderliche Bedingungen pro Laufzeitaktion

`invite`:

- keine Selbst-Einladung
- Zielbenutzer existiert
- Akteur und Ziel können Spiel-Einladungen empfangen
- Freundschafts- und Modulbeschränkungen auf Framework-Ebene sind erfüllt
- Verhindern doppelter offener Matches für dasselbe Paar und Spieltyp

`acceptInvite`, `declineInvite`, `cancelInvite`:

- Match existiert
- Akteur ist Teilnehmer
- Match gehört zum Laufzeit-`gameType`
- Zustand ist eingeladen
- Rollen-Bedingung (Einladender vs. Eingeladener) ist korrekt

`makeMove`:

- Match existiert
- Akteur ist Teilnehmer
- Match gehört zum Laufzeit-`gameType`
- Zustand ist aktiv
- Akteur ist aktueller Zug-Benutzer
- Payload ist strukturell gültig
- Zug ist laut Spielbrett/-zustand legal

`disable`:

- Eingeladene/aktive Matches des Laufzeit-Spieltyps schließen
- Zugehörige Benachrichtigungen bereinigen, falls verwendet

## Idempotenz-Empfehlungen

Empfohlenes Verhalten:

- Wenn eine wiederholte Anfrage dem bereits angewandten Zustand entspricht, Rückgabe des aktuellen Payloads statt Fehlerausgabe, wo möglich
- Strenge Fehler für tatsächlich ungültige oder nicht autorisierte Übergänge beibehalten
