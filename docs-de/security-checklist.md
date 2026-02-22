---
tags:
  - security
  - permissions
  - validation
---

# Sicherheits-Checkliste

Wenden Sie diese Checkliste für jede Laufzeit- und Endpunktintegration an.

## Eingabevalidierung

- Validieren Sie alle numerischen IDs als positive Ganzzahlen
- Validieren Sie Payload-Schlüssel, Typen und Wertebereiche
- Validieren Sie Enums explizit
- Lehnen Sie unbekannte oder unsichere Payload-Strukturen ab oder ignorieren Sie diese

## Autorisierung und Besitzverhältnisse

- Erfordern Sie einen authentifizierten Akteur
- Überprüfen Sie, ob der Akteur Teilnehmer des Spiels ist
- Überprüfen Sie, ob der Spieltyp des Spiels dem Laufzeittyp entspricht
- Überprüfen Sie die Rolle des Akteurs bei Einladungsaktionen (Einladender vs. Eingeladener)

## Durchsetzung von Zustandsübergängen

- Erzwingen Sie strikt erlaubte Ausgangszustände pro Aktion
- Erzwingen Sie das Zugrecht bei Zugaktionen
- Erzwingen Sie legale Zugbeschränkungen anhand der gespeicherten Spieldaten

## Missbrauchsverhinderung

- Verhindern Sie selbstbezogene Aktionen, wo nicht erlaubt
- Verhindern Sie doppelte offene Spiele für dasselbe Paar und Spieltyp
- Respektieren Sie Einladungseinstellungen zum Opt-out

## Daten- und Ausgabe-Sicherheit

- Geben Sie nur die benötigten Benutzerfelder in der Payload zurück
- Stellen Sie sicher, dass Payload-Schlüssel stabil und typensicher bleiben
- Halten Sie das Schema der Spieldaten explizit und bereinigt vor der Verwendung

## Betriebssicherheit

- Verlassen Sie sich bei allen ändernden Aufrufen auf Dienst-Transaktions-/Sperrgrenzen
- Vermeiden Sie Ad-hoc-Schreibvorgänge außerhalb des Dienst-/Laufzeitablaufs
