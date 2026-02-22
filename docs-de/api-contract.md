---
tags:
  - api
  - contract
  - endpoints
---

# HTTP API Vertrag

Alle Routen werden nach `gameType` geroutet.

## Endpunkte

- `POST /yabfs/games/{gameType}/invite`
- `POST /yabfs/games/{gameType}/matches/{id}/accept`
- `POST /yabfs/games/{gameType}/matches/{id}/decline`
- `POST /yabfs/games/{gameType}/matches/{id}/cancel`
- `POST /yabfs/games/{gameType}/matches/{id}/forfeit`
- `POST /yabfs/games/{gameType}/matches/{id}/move`
- `GET /yabfs/games/{gameType}/matches`
- `GET /yabfs/games/{gameType}/matches/{id}`
- `GET /yabfs/games/{gameType}/friends/search`

## Anfrage-Payloads

Einladung:
```json
{
  "userID": 123,
  "options": {
    "mode": "standard"
  }
}
```

Zug:
```json
{
  "position": 4
}
```

## Antwort-Payloads

Endpoints für Match-Änderungen liefern zurück:
```json
{
  "match": {
    "matchID": 42,
    "gameType": "myGame",
    "state": 1,
    "...": "..."
  }
}
```

Der Listen-Endpunkt liefert eine Snapshot-Hülle:
```json
{
  "unchanged": false,
  "lastActionTime": 1730000000,
  "items": []
}
```

Der einzelne Match-Endpunkt liefert eine Snapshot-Hülle:
```json
{
  "unchanged": false,
  "lastActionTime": 1730000000,
  "match": {}
}
```

## Long Polling

`GET /matches` und `GET /matches/{id}` unterstützen:

- `since=<lastActionTime>`
- `wait=<seconds>` (durch den Endpunkt begrenzt)

Verhalten:

- Wenn seit `since` keine Änderungen vorliegen, kann die Antwort `unchanged: true` sein.
- Wenn `wait` gesetzt ist, kann der Endpunkt blockieren, bis eine Änderung erfolgt oder ein Timeout eintritt.

## Freunde-Suchendpunkt

`GET /friends/search` Query-Parameter:

- `q` Suchstring
- `limit` serverseitig begrenzt

Antwort:

- `items[]` mit Benutzerübersichtsobjekten (`userID`, `username`, `avatar`, `isOnline`, `link`)
