---
tags:
  - acp
  - settings
  - configuration
---

# ACP Einstellungen Beispiel

Falls Ihr Spiel editierbare Einstellungen in der ACP-Spielkatalog-UI benötigt, implementieren Sie:

- `IYabfsGameAcpConfigurationProvider`

Methoden:

- `getDefaultCatalogSettings()`
- `getAcpCatalogSettingsFormNodes(array $settings)`
- `parseAcpCatalogSettingsFormData(array $formData)`
- `normalizeCatalogSettings(array $settings)`

## Minimales Beispiel

```php
<?php
final class MyGameProvider extends SingletonFactory implements IYabfsGameProvider, IYabfsGameAcpConfigurationProvider
{
    public function getDefaultCatalogSettings(): array
    {
        return [
            'mode' => 'standard',
            'maxTurns' => 20,
        ];
    }

    public function getAcpCatalogSettingsFormNodes(array $settings): array
    {
        // Gibt WoltLab-Formular-Knoten für mode/maxTurns zurück.
        return [];
    }

    public function parseAcpCatalogSettingsFormData(array $formData): array
    {
        $mode = isset($formData['mode']) && is_string($formData['mode']) ? trim($formData['mode']) : 'standard';
        $maxTurns = isset($formData['maxTurns']) ? (int)$formData['maxTurns'] : 20;

        return [
            'mode' => $mode,
            'maxTurns' => $maxTurns,
        ];
    }

    public function normalizeCatalogSettings(array $settings): array
    {
        $mode = isset($settings['mode']) && is_string($settings['mode']) ? trim($settings['mode']) : 'standard';
        if ($mode !== 'standard' && $mode !== 'ranked') {
            $mode = 'standard';
        }

        $maxTurns = isset($settings['maxTurns']) ? (int)$settings['maxTurns'] : 20;
        $maxTurns = max(1, min(200, $maxTurns));

        return [
            'mode' => $mode,
            'maxTurns' => $maxTurns,
        ];
    }
}
```

## Regeln

- Beide Pfade, parse und normalize, immer säubern  
- Standardwerte deterministisch halten  
- Frontend-Einladungsoptionen kompatibel mit den normalisierten ACP-Einstellungen halten
