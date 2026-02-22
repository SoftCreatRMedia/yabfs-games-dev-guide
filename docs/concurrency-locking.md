---
tags:
  - concurrency
  - locking
  - consistency
---

# Concurrency and Locking

## Why locking matters

Without locking, simultaneous requests can create race conditions:

- duplicate open invites
- double acceptance/decline transitions
- two moves applied in the same turn

## Framework locking model

The service layer acquires database locks before delegating runtime mutations:

- pair-level locking for invite creation (same user pair)
- match-level locking for accept/decline/cancel/forfeit/move

Operations are wrapped in DB transactions in service-level mutation methods.

## Runtime authoring rules

- assume concurrent requests are possible
- rely on locked read + validate + write pattern
- never trust stale frontend state
- re-read persisted state within the mutation path before writing

## Recommended mutation pattern

1. resolve and validate actor
2. load locked match row
3. validate state transition and payload
4. compute deterministic next state
5. update match row
6. append move row (if applicable)
7. return normalized payload

## Idempotency guidance

Prefer soft-idempotent behavior for retries:

- repeated same action against already-applied state may return current payload
- unauthorized or semantically invalid actions should still throw
