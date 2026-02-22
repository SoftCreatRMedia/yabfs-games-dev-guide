---
tags:
  - versioning
  - compatibility
  - dependencies
---

# Versionierung und Kompatibilität

## `gameData`-Schema-Strategie

Behandle `gameData` als versioniertes Dokument.

Empfohlene Struktur:

```json
{
  "schemaVersion": 1,
  "board": "...",
  "metadata": {}
}
```

## Kompatibilitätsregeln

- Der Laufzeit-Decoder muss fehlende optionale Schlüssel tolerieren  
- Der Laufzeit-Decoder muss ungültige Werte auf sichere Standardwerte normalisieren  
- Der Laufzeit-Encoder sollte stets die aktuelle kanonische Struktur ausgeben  

## Migrationsansatz

Bei Schema-Änderungen:  
1. Behalte die Rückwärtskompatibilität des Decoders für alte Versionen bei  
2. Normalisiere im Speicher zur neuesten Struktur  
3. Speichere die normalisierte Struktur beim nächsten Schreibvorgang erneut  

## Stabilität der öffentlichen Nutzlast

- Behalte erforderliche generische Nutzlast-Schlüssel unverändert  
- Füge spiel-spezifische Schlüssel rückwärtskompatibel hinzu  
- Vermeide das Entfernen von Schlüsseln, die von bestehenden Frontend-Modulen verwendet werden  

## Release-Management

- Erhöhe die Erweiterungsversion bei Änderungen an Laufzeit-/Nutzlast-Semantik  
- Dokumentiere Schema- und Verhaltensänderungen im Changelog  
- Füge Migrationshinweise für die Kompatibilität aktiver Matches hinzu
