---
tags:
  - schema
  - payload
  - validation
---

# Payload-Schemata und Validierung

Diese Seite bietet konkrete JSON-Schema-Dateien, die Sie wiederverwenden und erweitern können.

Wichtig:

- `match-payload.schema.json` beschreibt vom Host erforderliche generische Match-Felder.
- `invite-options.schema.json` und `move-payload.schema.json` sind strikte Startervorlagen für Erweiterungsautoren.
- YABFS erzwingt keine universelle, spiel-spezifische Payload-Struktur. Das Design Ihres spiel-spezifischen Schemas liegt bei Ihnen.

## Verfügbare Schema-Dateien

- [Match Payload Schema](assets/schemas/match-payload.schema.json)
- [Invite Options Schema Vorlage](assets/schemas/invite-options.schema.json)
- [Move Payload Schema Vorlage](assets/schemas/move-payload.schema.json)

## Verfügbare Beispiel-Payloads

- [Match Payload Beispiel](assets/examples/match-payload.example.json)
- [Invite Options Beispiel](assets/examples/invite-options.example.json)
- [Move Payload Beispiel](assets/examples/move-payload.example.json)

## Validierungsbefehl

```bash
python3 scripts/validate_schemas.py
```

Das Skript validiert alle mitgelieferten Beispiele gegen die mitgelieferten Schemata und gibt bei Fehlern einen Nicht-Null-Abschlusscode zurück.

## Verwendung in Ihrem eigenen Spielpaket

1. Kopieren Sie `invite-options.schema.json` und `move-payload.schema.json` als Ausgangspunkt.
2. Ersetzen Sie Platzhalter-Schlüssel durch Ihre spiel-spezifischen Schlüssel.
3. Belassen Sie die vom Host generischen Match-Schlüssel unverändert in den an die Basis-UI zurückgegebenen Payloads.
4. Validieren Sie Beispiel-Payloads in der CI, bevor Sie Ihr Paket veröffentlichen.
