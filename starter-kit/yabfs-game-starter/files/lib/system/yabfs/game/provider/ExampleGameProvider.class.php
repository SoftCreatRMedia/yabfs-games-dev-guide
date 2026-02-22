<?php

/*
 * Copyright by SoftCreatR.dev.
 *
 * License: MIT
 */

namespace wcf\system\yabfs\game\provider;

use wcf\system\SingletonFactory;
use wcf\system\yabfs\game\IYabfsGameProvider;
use wcf\system\yabfs\game\runtime\ExampleGameRuntime;

final class ExampleGameProvider extends SingletonFactory implements IYabfsGameProvider
{
    use TYabfsGameProviderTemplateDefaults;

    public function getType(): string
    {
        return 'exampleGame';
    }

    public function getRuntimeClassName(): string
    {
        return ExampleGameRuntime::class;
    }

    public function getTitleLanguageItem(): string
    {
        return 'wcf.user.yabfs.games.catalog.exampleGame.title';
    }

    public function getDescriptionLanguageItem(): string
    {
        return 'wcf.user.yabfs.games.catalog.exampleGame.description';
    }

    public function getRulesLanguageItem(): string
    {
        return 'wcf.user.yabfs.games.catalog.exampleGame.rules';
    }

    public function getPreviewImage(): ?string
    {
        return null;
    }

    public function getPreviewIcon(): string
    {
        return 'gamepad';
    }

    public function getPrimaryButtonLanguageItem(): string
    {
        return 'wcf.user.yabfs.games.catalog.exampleGame.button';
    }

    public function getFrontendModuleName(): string
    {
        return 'SoftCreatR/Yabfs/Ui/Page/Games/ExampleGame';
    }

    public function getPlayLanguageTemplateName(): string
    {
        return '__yabfsGamePlayLanguageExampleGame';
    }
}
