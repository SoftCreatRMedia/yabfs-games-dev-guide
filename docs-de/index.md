---
tags:
  - overview
  - onboarding
  - yabfs
---

# YABFS Spieleentwickler-Handbuch

Dieses Handbuch erklärt, wie eine neue Spielerweiterung für das YABFS Spiele-Framework implementiert wird.

Umfang:

- Framework-Architektur
- Laufzeitverträge
- HTTP-API-Vertrag
- Frontend-Integration
- Validierungs-, Sperr- und Teststrategie

Verwenden Sie dieses Handbuch als technische Anleitung und Beispielsammlung für Erweiterungen von Drittanbietern.

Lokale Vorschau:

```bash
pip install -r requirements.txt
zensical serve -f zensical.de.toml
```

## Inhalt

1. [Architektur](architecture.md)
2. [Laufzeit-Zustandsmaschine](runtime-state-machine.md)
3. [HTTP-API-Vertrag](api-contract.md)
4. [Starter-Kit Referenz](starter-kit-reference.md)
5. [Payload-Schemata und Validierung](payload-schema.md)
6. [Nebenläufigkeit und Sperrmechanismen](concurrency-locking.md)
7. [Sicherheitscheckliste](security-checklist.md)
8. [Troubleshooting-Anleitung](troubleshooting-playbook.md)
9. [Test-Matrix](testing-matrix.md)
10. [Minimaler Paketbaum](minimal-package-tree.md)
11. [ACP-Einstellungen Beispiel](acp-settings-example.md)
12. [Frontend-Integrationshinweise](frontend-ux-guidelines.md)
13. [Versionierung und Kompatibilität](versioning-compatibility.md)
14. [Minimales Beispiel](minimal-example.md)
