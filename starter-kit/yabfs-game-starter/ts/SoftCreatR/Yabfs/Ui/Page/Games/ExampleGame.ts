/*
 * Copyright by SoftCreatR.dev.
 *
 * License: MIT
 */

import type { GameInteractionContext, GameModule, GameRenderContext, MatchPayload } from "./Contracts";
import { registerGameModule } from "./Registry";

type ExampleGameMatch = MatchPayload & {
  exampleGameLastValue?: number | null;
  exampleGameMoveCount?: number;
};

function asExampleGameMatch(match: MatchPayload | null): ExampleGameMatch | null {
  if (match === null || match.gameType !== "exampleGame") {
    return null;
  }

  return match as ExampleGameMatch;
}

function renderBoard(context: GameRenderContext): string {
  const { match, buildActionButtons, escapeHtml, phrase } = context;
  const exampleMatch = asExampleGameMatch(match);

  if (exampleMatch === null) {
    return `<woltlab-core-notice type="info">${escapeHtml(phrase("wcf.user.yabfs.games.board.empty"))}</woltlab-core-notice>`;
  }

  const moveCount = Number.isFinite(exampleMatch.exampleGameMoveCount) ? Number(exampleMatch.exampleGameMoveCount) : 0;
  const lastValue =
    typeof exampleMatch.exampleGameLastValue === "number" ? String(exampleMatch.exampleGameLastValue) : "-";

  const status =
    exampleMatch.state === 0
      ? "Invite pending"
      : exampleMatch.state === 1
        ? exampleMatch.canMove
          ? "Your turn"
          : "Opponent turn"
        : exampleMatch.result === "win"
          ? "You won"
          : exampleMatch.result === "lose"
            ? "You lost"
            : "Finished";

  return `
    <div class="exampleGameBoard">
      <woltlab-core-notice type="info">${escapeHtml(status)}</woltlab-core-notice>
      <p><strong>Moves:</strong> ${moveCount} | <strong>Last Value:</strong> ${escapeHtml(lastValue)}</p>
      <button type="button" data-examplegame-action="play" ${exampleMatch.canMove ? "" : "disabled"}>
        ${escapeHtml(phrase("wcf.user.yabfs.games.board.exampleGame.play"))}
      </button>
      <div class="yabfsGameActions">${buildActionButtons(exampleMatch)}</div>
    </div>
  `;
}

function bindInteractions(context: GameInteractionContext): void {
  document.addEventListener("click", (event: Event) => {
    const button = (event.target as Element | null)?.closest<HTMLButtonElement>("[data-examplegame-action]");
    if (!button || context.getSelectedGameType() !== "exampleGame") {
      return;
    }

    const matchID = context.getSelectedMatchID();
    if (matchID <= 0) {
      return;
    }

    button.disabled = true;

    void context
      .makeMove(matchID, {
        value: 1
      })
      .then((updatedMatch) => {
        context.applyImmediateMatchUpdate(updatedMatch);
        void context.refreshAll(false);
      })
      .catch((error: unknown) => {
        context.onError(error, "Failed to submit example game move");
      })
      .finally(() => {
        button.disabled = false;
      });
  });
}

const module: GameModule = {
  gameType: "exampleGame",
  renderBoard,
  bindInteractions
};

registerGameModule(module);
