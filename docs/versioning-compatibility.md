---
tags:
  - versioning
  - compatibility
  - dependencies
---

# Versioning and Compatibility

## gameData schema strategy

Treat `gameData` as a versioned document.

Recommended structure:

```json
{
  "schemaVersion": 1,
  "board": "...",
  "metadata": {}
}
```

## Compatibility rules

- runtime decoder must tolerate missing optional keys
- runtime decoder must normalize invalid values to safe defaults
- runtime encoder should always emit current canonical structure

## Migration approach

When schema changes:
1. keep backward decoder support for old versions
2. normalize in memory to latest structure
3. re-save normalized structure on next write

## Public payload stability

- keep required generic payload keys unchanged
- add game-specific keys in backward-compatible way
- avoid removing keys used by existing frontend modules

## Release management

- bump extension version when runtime/payload semantics change
- note schema and behavior changes in changelog
- include migration notes for active-match compatibility
