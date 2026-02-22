#!/usr/bin/env python3
"""Build docs with Zensical and gracefully fall back to English-only output."""

from __future__ import annotations

import subprocess
from pathlib import Path

REPO_ROOT = Path(__file__).resolve().parent.parent
EN_CONFIG = REPO_ROOT / "zensical.toml"
DE_CONFIG = REPO_ROOT / "zensical.de.toml"
SITE_ROOT = REPO_ROOT / "site"


def run(command: list[str], *, check: bool = True) -> subprocess.CompletedProcess[str]:
    print(f"+ {' '.join(command)}")
    return subprocess.run(command, cwd=REPO_ROOT, check=check, text=True)


def build_english() -> None:
    run(["zensical", "build", "-c", "-f", str(EN_CONFIG)])


def build_german() -> bool:
    result = run(["zensical", "build", "-c", "-f", str(DE_CONFIG)], check=False)
    return result.returncode == 0


def write_root_redirect() -> None:
    SITE_ROOT.mkdir(parents=True, exist_ok=True)
    (SITE_ROOT / "index.html").write_text(
        """<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="0; url=./en/">
    <meta name="robots" content="noindex">
    <title>Redirecting...</title>
  </head>
  <body>
    <p>Redirecting to <a href="./en/">English documentation</a>.</p>
  </body>
</html>
""",
        encoding="utf-8",
    )


def write_de_redirect() -> None:
    de_dir = SITE_ROOT / "de"
    de_dir.mkdir(parents=True, exist_ok=True)
    (de_dir / "index.html").write_text(
        """<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="0; url=../en/">
    <meta name="robots" content="noindex">
    <title>Redirecting...</title>
  </head>
  <body>
    <p>German docs are currently unavailable. Redirecting to <a href="../en/">English documentation</a>.</p>
  </body>
</html>
""",
        encoding="utf-8",
    )


def main() -> int:
    build_english()

    german_built = build_german()
    if not german_built:
        print("German build failed. Keeping English build and writing /de redirect.")
        write_de_redirect()

    write_root_redirect()
    print("Build complete.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
