---
tags:
  - testing
  - qa
  - verification
---

# Testmatrix

Minimale Regressionsmatrix für jede neue Spielerweiterung.

| Bereich | Szenario | Erwartet |
|---|---|---|
| Einladung | gültige Einladung zwischen berechtigten Nutzern | eingeladene Partie erstellt |
| Einladung | Selbst-Einladung | abgelehnt |
| Einladung | doppelte offene Einladung | bestehende Partie wiederverwendet oder Duplikat blockiert |
| Akzeptieren | Eingeladener akzeptiert Partie | Zustand wird aktiv |
| Akzeptieren | Einladender versucht zu akzeptieren | abgelehnt |
| Ablehnen | Eingeladener lehnt Partie ab | Zustand wird abgelehnt |
| Abbrechen | Einladender bricht Partie ab | Zustand wird abgebrochen |
| Zug | gültiger Zug bei aktivem Zug | Spielfeld und Zug aktualisiert |
| Zug | Nutzer am falschen Zug macht Zug | abgelehnt |
| Zug | ungültige Nutzlast (außerhalb des Bereichs) | abgelehnt |
| Beenden | Gewinn-/Unentschieden-Bedingung erreicht | Zustand beendet und Ergebnis gesetzt |
| Aufgeben | Teilnehmer gibt aktive Partie auf | Zustand beendet, Sieger auf Gegner gesetzt |
| Abfrage | Liste mit `since`, keine Änderungen | unveränderte Antwort |
| Abfrage | Liste mit `since` nach Zug | geänderte Momentaufnahme in Antwort |
| Sicherheit | Akteur ohne Teilnahmezugriff | abgelehnt |
| Sprache | JS-Phrasen injiziert | keine fehlenden Phrasen in UI |
| Katalog | Anbieterobjekttyp registriert | Spiel im Katalog sichtbar |

## Empfohlene Automatisierung Aufteilung

- Backend-Integrationstests für Laufzeitübergänge
- API-Tests für Endpoint-Nutzlast- und Berechtigungsverhalten
- Frontend-Smoke-Tests für Darstellung und Aktionsverbindungen
