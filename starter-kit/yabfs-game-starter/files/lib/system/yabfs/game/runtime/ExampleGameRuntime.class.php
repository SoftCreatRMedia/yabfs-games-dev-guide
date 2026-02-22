<?php

/*
 * Copyright by SoftCreatR.dev.
 *
 * License: MIT
 */

namespace wcf\system\yabfs\game\runtime;

use wcf\data\yabfs\game\match\GameMatch;
use wcf\data\yabfs\game\move\GameMoveEditor;
use wcf\system\cache\runtime\UserProfileRuntimeCache;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\system\yabfs\FriendshipService;
use wcf\util\JSON;

final class ExampleGameRuntime implements IYabfsGameRuntime
{
    public function getType(): string
    {
        return 'exampleGame';
    }

    /**
     * @inheritDoc
     */
    public function listMatchesForUser(int $userID, int $limit = 50): array
    {
        if ($limit < 1) {
            $limit = 1;
        }

        $statement = WCF::getDB()->prepare(
            "SELECT matchID
             FROM wcf1_yabfs_game_match
             WHERE gameType = ?
               AND state IN (?, ?, ?)
               AND (xUserID = ? OR oUserID = ?)
             ORDER BY lastActionTime DESC",
            $limit
        );
        $statement->execute([
            $this->getType(),
            GameMatch::STATE_INVITED,
            GameMatch::STATE_ACTIVE,
            GameMatch::STATE_FINISHED,
            $userID,
            $userID,
        ]);

        $items = [];

        while (($value = $statement->fetchSingleColumn()) !== false) {
            $match = new GameMatch((int)$value);

            if (!$match->matchID || !$match->isParticipant($userID)) {
                continue;
            }

            $items[] = $this->toMatchPayload($match, $userID, false);
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function listMatchesSnapshotForUser(int $userID, int $limit = 50, ?int $since = null): array
    {
        $lastActionTime = $this->getLatestActionTimeForUser($userID);

        if ($since !== null && $since > 0 && $lastActionTime <= $since) {
            return [
                'unchanged' => true,
                'lastActionTime' => $lastActionTime,
                'items' => [],
            ];
        }

        return [
            'unchanged' => false,
            'lastActionTime' => $lastActionTime,
            'items' => $this->listMatchesForUser($userID, $limit),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getMatchPayloadForUser(int $matchID, int $userID): array
    {
        $match = $this->requireMatchForUser($matchID, $userID);

        return $this->toMatchPayload($match, $userID, true);
    }

    /**
     * @inheritDoc
     */
    public function getMatchSnapshotForUser(int $matchID, int $userID, ?int $since = null): array
    {
        $match = $this->requireMatchForUser($matchID, $userID);
        $lastActionTime = $match->lastActionTime;

        if ($since !== null && $since > 0 && $lastActionTime <= $since) {
            return [
                'unchanged' => true,
                'lastActionTime' => $lastActionTime,
            ];
        }

        return [
            'unchanged' => false,
            'lastActionTime' => $lastActionTime,
            'match' => $this->toMatchPayload($match, $userID, true),
        ];
    }

    /**
     * @inheritDoc
     */
    public function invite(int $fromUserID, int $toUserID, array $options = []): array
    {
        if ($fromUserID === $toUserID) {
            throw new UserInputException('userID');
        }

        $targetUser = UserProfileRuntimeCache::getInstance()->getObject($toUserID);
        if ($targetUser === null || !$targetUser->userID) {
            throw new UserInputException('userID');
        }

        if (!FriendshipService::getInstance()->areFriends($fromUserID, $toUserID)) {
            throw new PermissionDeniedException();
        }

        $existingMatchID = $this->findOpenMatchID($fromUserID, $toUserID);
        if ($existingMatchID !== null) {
            return $this->getMatchPayloadForUser($existingMatchID, $fromUserID);
        }

        $statement = WCF::getDB()->prepare(
            "INSERT INTO wcf1_yabfs_game_match
                (gameType, state, xUserID, oUserID, invitedByUserID, currentTurnUserID, winnerUserID,
                 gameData, createdTime, startedTime, finishedTime, lastMoveTime, lastActionTime)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $statement->execute([
            $this->getType(),
            GameMatch::STATE_INVITED,
            $fromUserID,
            $toUserID,
            $fromUserID,
            null,
            null,
            $this->encodeGameData([
                'moves' => [],
            ]),
            TIME_NOW,
            null,
            null,
            null,
            TIME_NOW,
        ]);

        $matchID = (int)WCF::getDB()->getInsertID('wcf1_yabfs_game_match', 'matchID');

        return $this->getMatchPayloadForUser($matchID, $fromUserID);
    }

    /**
     * @inheritDoc
     */
    public function acceptInvite(int $matchID, int $userID): array
    {
        $match = $this->requireMatchForUser($matchID, $userID);

        if ($match->state === GameMatch::STATE_ACTIVE) {
            return $this->getMatchPayloadForUser($match->matchID, $userID);
        }

        if ($match->state !== GameMatch::STATE_INVITED) {
            throw new UserInputException('matchID', 'invalidState');
        }

        if ($match->invitedByUserID === $userID) {
            throw new PermissionDeniedException();
        }

        $startingUserID = $match->invitedByUserID > 0 ? $match->invitedByUserID : $match->xUserID;

        $this->updateMatch($match->matchID, [
            'state' => GameMatch::STATE_ACTIVE,
            'currentTurnUserID' => $startingUserID,
            'startedTime' => $match->startedTime ?: TIME_NOW,
            'lastActionTime' => TIME_NOW,
        ]);

        return $this->getMatchPayloadForUser($match->matchID, $userID);
    }

    /**
     * @inheritDoc
     */
    public function declineInvite(int $matchID, int $userID): array
    {
        $match = $this->requireMatchForUser($matchID, $userID);

        if ($match->state === GameMatch::STATE_DECLINED) {
            return $this->getMatchPayloadForUser($match->matchID, $userID);
        }

        if ($match->state !== GameMatch::STATE_INVITED) {
            throw new UserInputException('matchID', 'invalidState');
        }

        if ($match->invitedByUserID === $userID) {
            throw new PermissionDeniedException();
        }

        $this->updateMatch($match->matchID, [
            'state' => GameMatch::STATE_DECLINED,
            'currentTurnUserID' => null,
            'finishedTime' => TIME_NOW,
            'lastActionTime' => TIME_NOW,
        ]);

        return $this->getMatchPayloadForUser($match->matchID, $userID);
    }

    /**
     * @inheritDoc
     */
    public function cancelInvite(int $matchID, int $userID): array
    {
        $match = $this->requireMatchForUser($matchID, $userID);

        if ($match->state === GameMatch::STATE_CANCELLED) {
            return $this->getMatchPayloadForUser($match->matchID, $userID);
        }

        if ($match->state !== GameMatch::STATE_INVITED) {
            throw new UserInputException('matchID', 'invalidState');
        }

        if ($match->invitedByUserID !== $userID) {
            throw new PermissionDeniedException();
        }

        $this->updateMatch($match->matchID, [
            'state' => GameMatch::STATE_CANCELLED,
            'currentTurnUserID' => null,
            'finishedTime' => TIME_NOW,
            'lastActionTime' => TIME_NOW,
        ]);

        return $this->getMatchPayloadForUser($match->matchID, $userID);
    }

    /**
     * @inheritDoc
     */
    public function makeMove(int $matchID, int $userID, array $movePayload): array
    {
        $value = (int)($movePayload['value'] ?? 0);
        if ($value < 1 || $value > 9) {
            throw new UserInputException('value');
        }

        $match = $this->requireMatchForUser($matchID, $userID);
        $gameData = $this->decodeGameData($match->gameData);
        $moves = $gameData['moves'];

        if ($match->state !== GameMatch::STATE_ACTIVE) {
            if ($match->state === GameMatch::STATE_FINISHED && (int)$match->winnerUserID === $userID) {
                return $this->getMatchPayloadForUser($match->matchID, $userID);
            }

            throw new UserInputException('matchID', 'invalidState');
        }

        if ((int)$match->currentTurnUserID !== $userID) {
            throw new PermissionDeniedException();
        }

        $turnNo = \count($moves) + 1;
        $moves[] = [
            'userID' => $userID,
            'value' => $value,
            'time' => TIME_NOW,
        ];

        $this->updateMatch($match->matchID, [
            'state' => GameMatch::STATE_FINISHED,
            'winnerUserID' => $userID,
            'currentTurnUserID' => null,
            'gameData' => $this->encodeGameData([
                'moves' => $moves,
            ]),
            'lastMoveTime' => TIME_NOW,
            'finishedTime' => TIME_NOW,
            'lastActionTime' => TIME_NOW,
        ]);

        $moveData = JSON::encode([
            'value' => $value,
        ]);
        if ($moveData === '') {
            $moveData = '{"value":1}';
        }

        GameMoveEditor::create([
            'matchID' => $match->matchID,
            'userID' => $userID,
            'turnNo' => $turnNo,
            'moveData' => $moveData,
            'moveTime' => TIME_NOW,
        ]);

        return $this->getMatchPayloadForUser($match->matchID, $userID);
    }

    /**
     * @inheritDoc
     */
    public function disable(): int
    {
        $statement = WCF::getDB()->prepare(
            "UPDATE wcf1_yabfs_game_match
             SET state = ?,
                 currentTurnUserID = NULL,
                 finishedTime = ?,
                 lastActionTime = ?
             WHERE gameType = ?
               AND state IN (?, ?)"
        );
        $statement->execute([
            GameMatch::STATE_CANCELLED,
            TIME_NOW,
            TIME_NOW,
            $this->getType(),
            GameMatch::STATE_INVITED,
            GameMatch::STATE_ACTIVE,
        ]);

        return $statement->getAffectedRows();
    }

    private function getLatestActionTimeForUser(int $userID): int
    {
        $statement = WCF::getDB()->prepare(
            "SELECT MAX(lastActionTime)
             FROM wcf1_yabfs_game_match
             WHERE gameType = ?
               AND (xUserID = ? OR oUserID = ?)"
        );
        $statement->execute([
            $this->getType(),
            $userID,
            $userID,
        ]);

        $value = $statement->fetchSingleColumn();
        if (!\is_numeric($value)) {
            return 0;
        }

        return (int)$value;
    }

    private function findOpenMatchID(int $userID1, int $userID2): ?int
    {
        $statement = WCF::getDB()->prepare(
            "SELECT matchID
             FROM wcf1_yabfs_game_match
             WHERE gameType = ?
               AND state IN (?, ?)
               AND (
                 (xUserID = ? AND oUserID = ?)
                 OR
                 (xUserID = ? AND oUserID = ?)
               )",
            1
        );
        $statement->execute([
            $this->getType(),
            GameMatch::STATE_INVITED,
            GameMatch::STATE_ACTIVE,
            $userID1,
            $userID2,
            $userID2,
            $userID1,
        ]);
        $matchID = $statement->fetchSingleColumn();

        if ($matchID === false) {
            return null;
        }

        return (int)$matchID;
    }

    private function requireMatchForUser(int $matchID, int $userID): GameMatch
    {
        $match = new GameMatch($matchID);

        if (!$match->matchID) {
            throw new UserInputException('matchID');
        }

        if (!$match->isParticipant($userID)) {
            throw new PermissionDeniedException();
        }

        if ($match->gameType !== $this->getType()) {
            throw new UserInputException('matchID');
        }

        return $match;
    }

    /**
     * @param array<string, int|null|string> $data
     */
    private function updateMatch(int $matchID, array $data): void
    {
        if ($data === []) {
            return;
        }

        $set = [];
        $parameters = [];

        foreach ($data as $column => $value) {
            $set[] = $column . ' = ?';
            $parameters[] = $value;
        }

        $parameters[] = $matchID;

        $statement = WCF::getDB()->prepare(
            "UPDATE wcf1_yabfs_game_match
             SET " . \implode(', ', $set) . "
             WHERE matchID = ?"
        );
        $statement->execute($parameters);
    }

    /**
     * @return array{moves: array<int, array{userID: int, value: int, time: int}>}
     *
     * @throws SystemException
     */
    private function decodeGameData(?string $rawData): array
    {
        $moves = [];

        if (\is_string($rawData) && $rawData !== '') {
            $decoded = JSON::decode($rawData);
            $candidateMoves = \is_array($decoded) ? ($decoded['moves'] ?? null) : null;

            if (\is_array($candidateMoves)) {
                foreach ($candidateMoves as $row) {
                    if (!\is_array($row)) {
                        continue;
                    }

                    $moveUserID = (int)($row['userID'] ?? 0);
                    $moveValue = (int)($row['value'] ?? 0);
                    $moveTime = (int)($row['time'] ?? 0);

                    if ($moveUserID <= 0 || $moveValue < 1 || $moveValue > 9 || $moveTime <= 0) {
                        continue;
                    }

                    $moves[] = [
                        'userID' => $moveUserID,
                        'value' => $moveValue,
                        'time' => $moveTime,
                    ];
                }
            }
        }

        return [
            'moves' => $moves,
        ];
    }

    /**
     * @param array{moves: array<int, array{userID: int, value: int, time: int}>} $data
     */
    private function encodeGameData(array $data): string
    {
        $encoded = JSON::encode($data);

        if ($encoded === '') {
            return '{"moves":[]}';
        }

        return $encoded;
    }

    /**
     * @return array<string, mixed>
     */
    private function toMatchPayload(GameMatch $match, int $viewerUserID, bool $includeDetails): array
    {
        $opponentUserID = $match->getOpponentUserID($viewerUserID);
        $opponent = UserProfileRuntimeCache::getInstance()->getObject($opponentUserID);
        $gameData = $this->decodeGameData($match->gameData);
        $moves = $gameData['moves'];
        $moveCount = \count($moves);
        $lastMove = $moveCount > 0 ? $moves[$moveCount - 1] : null;

        $state = $match->state;
        $isInvited = $state === GameMatch::STATE_INVITED;
        $isActive = $state === GameMatch::STATE_ACTIVE;
        $isFinished = $state === GameMatch::STATE_FINISHED;
        $isYourTurn = $isActive && (int)$match->currentTurnUserID === $viewerUserID;

        $result = null;
        if ($isFinished) {
            $winnerUserID = (int)($match->winnerUserID ?? 0);
            if ($winnerUserID === 0) {
                $result = 'draw';
            } elseif ($winnerUserID === $viewerUserID) {
                $result = 'win';
            } else {
                $result = 'lose';
            }
        }

        return [
            'matchID' => $match->matchID,
            'gameType' => $match->gameType,
            'state' => $state,
            'invitedByUserID' => $match->invitedByUserID,
            'xUserID' => $match->xUserID,
            'oUserID' => $match->oUserID,
            'currentTurnUserID' => $match->currentTurnUserID !== null ? (int)$match->currentTurnUserID : null,
            'winnerUserID' => $match->winnerUserID !== null ? (int)$match->winnerUserID : null,
            'yourUserID' => $viewerUserID,
            'yourSymbol' => $viewerUserID === $match->xUserID ? 'X' : 'O',
            'isYourTurn' => $isYourTurn,
            'canMove' => $isYourTurn,
            'canAcceptInvite' => $isInvited && $match->invitedByUserID !== $viewerUserID,
            'canDeclineInvite' => $isInvited && $match->invitedByUserID !== $viewerUserID,
            'canCancelInvite' => $isInvited && $match->invitedByUserID === $viewerUserID,
            'isFinished' => $isFinished,
            'result' => $result,
            'opponent' => [
                'userID' => $opponentUserID,
                'username' => $opponent?->username ?? ('#' . $opponentUserID),
                'link' => $opponent?->getLink() ?? '',
                'avatar' => $opponent?->getAvatar()->getImageTag(48) ?? '',
                'isOnline' => $opponent?->isOnline() ?? false,
            ],
            'exampleGameMoveCount' => $moveCount,
            'exampleGameLastValue' => $lastMove['value'] ?? null,
            'exampleGameMoves' => $includeDetails ? $moves : [],
            'createdTime' => $match->createdTime,
            'startedTime' => $match->startedTime !== null ? (int)$match->startedTime : null,
            'finishedTime' => $match->finishedTime !== null ? (int)$match->finishedTime : null,
            'lastMoveTime' => $match->lastMoveTime !== null ? (int)$match->lastMoveTime : null,
            'lastActionTime' => $match->lastActionTime,
        ];
    }
}
