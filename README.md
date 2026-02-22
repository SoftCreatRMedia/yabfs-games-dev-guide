# YABFS Games Dev Guide

Standalone documentation project for building game extensions for YABFS.

Repository: `https://github.com/SoftCreatRMedia/yabfs-games-dev-guide`

## Local Preview

```bash
python3 -m pip install --user -r requirements.txt
python3 scripts/build_site.py
python3 -m http.server 8000 --directory site
```

Then open:
- `http://127.0.0.1:8000/` (redirects to `/en/`)
- `http://127.0.0.1:8000/en/`
- `http://127.0.0.1:8000/de/`

`scripts/build_site.py` uses Zensical and automatically falls back to EN-only output if the DE build fails.

## GitHub Hosting

This repository is configured for GitHub Pages via GitHub Actions.

1. Push to `main`.
2. In GitHub repository settings, open `Pages`.
3. Set `Source` to `GitHub Actions`.
4. The workflow `.github/workflows/deploy.yml` publishes automatically.

Expected URL:
`https://softcreatrmedia.github.io/yabfs-games-dev-guide/`

## Structure

- `docs/` English markdown source files
- `docs-de/` German markdown source files
- `docs/assets/schemas/` JSON Schemas used in documentation examples
- `docs/assets/examples/` schema-validated sample payloads
- `zensical.toml` English build config (`/en/`)
- `zensical.de.toml` German build config (`/de/`)
- `.github/workflows/deploy.yml` automatic deployment
- `scripts/build_site.py` Zensical build entrypoint used locally and in CI
- `scripts/validate_schemas.py` schema check used in CI
- `starter-kit/yabfs-game-starter/` cloneable starter extension scaffold
