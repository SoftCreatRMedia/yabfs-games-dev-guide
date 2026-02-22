# YABFS Game Starter Package

This scaffold is a fully working instruction-by-example package for building a custom YABFS game extension.

## Rename Checklist

Replace all `exampleGame` identifiers with your own game type token.

Files that must be renamed/adjusted:

- PHP class names and namespaces
- language keys under `wcf.user.yabfs.games.catalog.exampleGame.*`
- TypeScript module file and `gameType` value
- template file `__yabfsGamePlayLanguageExampleGame.tpl`
- object type technical name

## Dependency Baseline

- `com.woltlab.wcf` `6.2.0`
- `de.softcreatr.wsc.yabfs.games` `1.0.0`

## Included language files

- `en, de, cs, da, es, fr, hu, it, nl, no, pl, ro, ru, sv, tr`

## Notes

- Runtime class is fully implemented with a minimal one-move game flow.
- This starter does not include custom database migration files because it uses the host `wcf1_yabfs_game_match` and `wcf1_yabfs_game_move` tables.

## Build commands

```bash
npm i
npm run build
npm run lint
```
