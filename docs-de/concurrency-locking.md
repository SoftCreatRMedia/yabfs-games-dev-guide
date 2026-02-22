---
tags:
  - concurrency
  - locking
  - consistency
---

# Nebenläufigkeit und Sperrverwaltung

## Warum Sperren wichtig sind

Ohne Sperren können gleichzeitige Anfragen zu Race Conditions führen:

- doppelte offene Einladungen
- doppelte Akzeptanz-/Ablehnungsübergänge
- zwei Züge im gleichen Spielzug angewendet

## Sperrmodell des Frameworks

Die Service-Schicht erwirbt Datenbanksperren, bevor sie Laufzeitmutationen delegiert:

- Sperren auf Paar-Ebene für Einladungs-Erstellung (gleiches Benutzerpaar)
- Sperren auf Spiel-Ebene für Akzeptieren/Ablehnen/Abbrechen/Verlieren/Zug

Operationen sind in DB-Transaktionen innerhalb servicebasierter Mutationsmethoden gekapselt.

## Laufzeit-Regeln für Änderungen

- Gleichzeitige Anfragen werden angenommen
- Verlasse dich auf Muster: gesperrtes Lesen + Validieren + Schreiben
- Vertraue niemals veralteten Frontend-Zuständen
- Lies den persistierten Zustand innerhalb des Mutationspfads vor dem Schreiben erneut

## Empfohlenes Mutationsmuster

1. Akteur auflösen und validieren  
2. Gesperrte Spielzeile laden  
3. Statusübergang und Payload validieren  
4. Deterministischen nächsten Zustand berechnen  
5. Spielzeile aktualisieren  
6. Zugzeile anhängen (falls zutreffend)  
7. Normalisierte Payload zurückgeben

## Richtlinien zur Idempotenz

Bevorzuge soft-idempotentes Verhalten bei Wiederholungen:

- Wiederholte gleiche Aktion auf bereits angewendetem Zustand kann aktuellen Payload zurückgeben  
- Nicht autorisierte oder semantisch ungültige Aktionen sollten weiterhin Fehler auslösen
