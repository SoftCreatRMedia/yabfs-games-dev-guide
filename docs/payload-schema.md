---
tags:
  - schema
  - payload
  - validation
---

# Payload Schemas and Validation

This page provides concrete JSON Schema files you can reuse and extend.

Important:

- `match-payload.schema.json` describes host-required generic match fields.
- `invite-options.schema.json` and `move-payload.schema.json` are strict starter templates for extension authors.
- YABFS does not enforce one universal game-specific payload shape. You own your game-specific schema design.

## Published schema files

- [Match Payload Schema](assets/schemas/match-payload.schema.json)
- [Invite Options Schema Template](assets/schemas/invite-options.schema.json)
- [Move Payload Schema Template](assets/schemas/move-payload.schema.json)

## Published example payloads

- [Match Payload Example](assets/examples/match-payload.example.json)
- [Invite Options Example](assets/examples/invite-options.example.json)
- [Move Payload Example](assets/examples/move-payload.example.json)

## Validation command

```bash
python3 scripts/validate_schemas.py
```

The script validates all shipped examples against the shipped schemas and exits non-zero on failure.

## How to use in your own game package

1. Copy `invite-options.schema.json` and `move-payload.schema.json` as a starting point.
2. Replace placeholder keys with your game-specific keys.
3. Keep the host generic match keys untouched in payloads returned to base UI.
4. Validate example payloads in CI before publishing your package.
