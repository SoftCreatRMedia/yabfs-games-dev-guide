---
tags:
  - acp
  - settings
  - configuration
---

# ACP Settings Example

If your game needs editable settings in ACP game catalog UI, implement:

- `IYabfsGameAcpConfigurationProvider`

Methods:

- `getDefaultCatalogSettings()`
- `getAcpCatalogSettingsFormNodes(array $settings)`
- `parseAcpCatalogSettingsFormData(array $formData)`
- `normalizeCatalogSettings(array $settings)`

## Minimal example

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
        // Return WoltLab form nodes for mode/maxTurns.
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

## Rules

- always sanitize both parse and normalize paths
- keep defaults deterministic
- keep frontend invite options compatible with normalized ACP settings
