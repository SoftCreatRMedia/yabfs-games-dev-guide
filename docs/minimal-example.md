---
tags:
  - example
  - walkthrough
  - integration
---

# Minimal Working Example

This is a minimal game named `miniGame`.
Behavior: first valid move immediately finishes match with mover as winner.

## 1) Provider

```php
<?php
final class MiniGameProvider extends SingletonFactory implements IYabfsGameProvider
{
    use TYabfsGameProviderTemplateDefaults;

    public function getType(): string { return 'miniGame'; }
    public function getRuntimeClassName(): string { return MiniGameRuntime::class; }
    public function getTitleLanguageItem(): string { return 'wcf.user.yabfs.games.catalog.miniGame.title'; }
    public function getDescriptionLanguageItem(): string { return 'wcf.user.yabfs.games.catalog.miniGame.description'; }
    public function getRulesLanguageItem(): string { return 'wcf.user.yabfs.games.catalog.miniGame.rules'; }
    public function getPreviewImage(): ?string { return null; }
    public function getPreviewIcon(): string { return 'bolt'; }
    public function getPrimaryButtonLanguageItem(): string { return 'wcf.user.yabfs.games.catalog.miniGame.button'; }
    public function getFrontendModuleName(): string { return 'SoftCreatR/Yabfs/Ui/Page/Games/MiniGame'; }
    public function getPlayLanguageTemplateName(): string { return '__yabfsGamePlayLanguageMiniGame'; }
}
```

## 2) Object type registration

```xml
<type>
    <name>vendor.yabfs.game.minigame</name>
    <definitionname>de.softcreatr.wsc.yabfs.game</definitionname>
    <classname>wcf\system\yabfs\game\provider\MiniGameProvider</classname>
    <category>de.softcreatr.wsc.yabfs</category>
</type>
```

## 3) Runtime

```php
<?php
final class MiniGameRuntime implements IYabfsGameRuntime
{
    public function getType(): string { return 'miniGame'; }

    public function invite(int $fromUserID, int $toUserID, array $options = []): array
    {
        // Create invited match with initial gameData and return payload.
    }

    public function makeMove(int $matchID, int $userID, array $movePayload): array
    {
        // Validate active state and participant.
        // Set winnerUserID = actor and state = finished.
        // Persist and return payload.
    }

    // Implement all other methods from IYabfsGameRuntime.
}
```

## 4) Frontend module

```ts
import type { GameInteractionContext, GameModule, GameRenderContext } from "./Contracts";
import { registerGameModule } from "./Registry";

function renderBoard(context: GameRenderContext): string {
  const { match, escapeHtml, phrase, buildActionButtons } = context;
  if (!match || match.gameType !== "miniGame") {
    return `<woltlab-core-notice type="info">${escapeHtml(phrase("wcf.user.yabfs.games.board.empty"))}</woltlab-core-notice>`;
  }

  return `<div class="miniGameBoard">
    <button type="button" data-mini-move="1" ${match.canMove ? "" : "disabled"}>Play</button>
    <div class="yabfsTttBoardActions">${buildActionButtons(match)}</div>
  </div>`;
}

function bindInteractions(context: GameInteractionContext): void {
  document.addEventListener("click", (event) => {
    const button = (event.target as Element | null)?.closest<HTMLButtonElement>("[data-mini-move]");
    if (!button || context.getSelectedGameType() !== "miniGame") return;

    const matchID = context.getSelectedMatchID();
    if (matchID <= 0) return;

    void context.makeMove(matchID, { move: 1 }).then((updated) => {
      context.applyImmediateMatchUpdate(updated);
      void context.refreshAll(false);
    });
  });
}

registerGameModule({
  gameType: "miniGame",
  renderBoard,
  bindInteractions,
});
```

## 5) JS language injection

`templateListener.xml`:

```xml
<templatelistener name="miniGamePlayLanguage">
    <environment>user</environment>
    <templatename>gamesPlay</templatename>
    <eventname>yabfsGamePlayLanguageItems</eventname>
    <templatecode><![CDATA[{include file='__yabfsGamePlayLanguageMiniGame'}]]></templatecode>
</templatelistener>
```

`templates/__yabfsGamePlayLanguageMiniGame.tpl`:

```tpl
'wcf.user.yabfs.games.board.miniGame.play': '{jslang}wcf.user.yabfs.games.board.miniGame.play{/jslang}',
```

## 6) Build

```bash
npm i
npx tsc
```
