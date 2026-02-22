---
tags:
  - security
  - permissions
  - validation
---

# Security Checklist

Apply this checklist for every runtime and endpoint integration.

## Input validation

- validate all numeric IDs as positive integers
- validate payload keys, types, and ranges
- validate enums explicitly
- reject or ignore unknown unsafe payload structure

## Authorization and ownership

- require authenticated actor
- verify actor is match participant
- verify match gameType equals runtime type
- verify actor role for invite actions (inviter vs invitee)

## State transition enforcement

- enforce strict allowed-from states per action
- enforce turn ownership for move actions
- enforce legal move constraints against persisted gameData

## Abuse controls

- prevent self-target actions where not allowed
- prevent duplicate open matches for same pair and game type
- respect invitation opt-out settings

## Data and output safety

- return only needed user fields in payload
- ensure payload keys remain stable and type-safe
- keep gameData schema explicit and sanitized before use

## Operational safety

- rely on service transaction/lock boundaries for all mutating calls
- avoid ad-hoc writes outside service/runtime flow
