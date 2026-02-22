---
tags:
  - overview
  - onboarding
  - yabfs
---

# YABFS Games Developer Handbook

This handbook explains how to implement a new game extension for the YABFS game framework.

Scope:

- framework architecture
- runtime contracts
- HTTP API contract
- frontend integration
- validation, locking, and testing strategy

Use this handbook as a technical instruction set and example library for third-party game extensions.

Local preview:

```bash
pip install -r requirements.txt
zensical serve -f zensical.toml
```

## Contents

1. [Architecture](architecture.md)
2. [Runtime State Machine](runtime-state-machine.md)
3. [HTTP API Contract](api-contract.md)
4. [Starter Kit Reference](starter-kit-reference.md)
5. [Payload Schemas and Validation](payload-schema.md)
6. [Concurrency and Locking](concurrency-locking.md)
7. [Security Checklist](security-checklist.md)
8. [Troubleshooting Playbook](troubleshooting-playbook.md)
9. [Testing Matrix](testing-matrix.md)
10. [Minimal Package Tree](minimal-package-tree.md)
11. [ACP Settings Example](acp-settings-example.md)
12. [Frontend Integration Notes](frontend-ux-guidelines.md)
13. [Versioning and Compatibility](versioning-compatibility.md)
14. [Minimal Working Example](minimal-example.md)
