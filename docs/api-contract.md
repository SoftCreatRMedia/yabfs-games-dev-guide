---
tags:
  - api
  - contract
  - endpoints
---

# HTTP API Contract

All routes are routed by `gameType`.

## Endpoints

- `POST /yabfs/games/{gameType}/invite`
- `POST /yabfs/games/{gameType}/matches/{id}/accept`
- `POST /yabfs/games/{gameType}/matches/{id}/decline`
- `POST /yabfs/games/{gameType}/matches/{id}/cancel`
- `POST /yabfs/games/{gameType}/matches/{id}/forfeit`
- `POST /yabfs/games/{gameType}/matches/{id}/move`
- `GET /yabfs/games/{gameType}/matches`
- `GET /yabfs/games/{gameType}/matches/{id}`
- `GET /yabfs/games/{gameType}/friends/search`

## Request payloads

Invite:
```json
{
  "userID": 123,
  "options": {
    "mode": "standard"
  }
}
```

Move:
```json
{
  "position": 4
}
```

## Response payloads

Match mutation endpoints return:
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

List endpoint returns snapshot envelope:
```json
{
  "unchanged": false,
  "lastActionTime": 1730000000,
  "items": []
}
```

Single match endpoint returns snapshot envelope:
```json
{
  "unchanged": false,
  "lastActionTime": 1730000000,
  "match": {}
}
```

## Long polling

`GET /matches` and `GET /matches/{id}` support:

- `since=<lastActionTime>`
- `wait=<seconds>` (bounded by endpoint max)

Behavior:

- if no changes since `since`, response can be `unchanged: true`
- if `wait` is set, endpoint can block until change or timeout

## Friends search endpoint

`GET /friends/search` query params:

- `q` search string
- `limit` bounded server-side

Response:

- `items[]` with user summary objects (`userID`, `username`, `avatar`, `isOnline`, `link`)
