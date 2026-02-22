/*
 * Copyright by SoftCreatR.dev.
 *
 * License: MIT
 */

import type { GameModule } from "./Contracts";

type BaseRegistryModule = {
  registerGameModule?: (gameModule: GameModule) => void;
};

declare function require(modules: string[], callback: (module: BaseRegistryModule) => void): void;

export function registerGameModule(gameModule: GameModule): void {
  require(["SoftCreatR/Yabfs/Ui/Page/Games/Registry"], (registry: BaseRegistryModule) => {
    if (typeof registry.registerGameModule === "function") {
      registry.registerGameModule(gameModule);
    }
  });
}
