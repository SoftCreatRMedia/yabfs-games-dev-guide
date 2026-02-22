#!/usr/bin/env python3

from __future__ import annotations

import json
import sys
from pathlib import Path

from jsonschema import Draft202012Validator


ROOT = Path(__file__).resolve().parent.parent

VALIDATION_PAIRS = [
    (
        ROOT / "docs/assets/schemas/match-payload.schema.json",
        ROOT / "docs/assets/examples/match-payload.example.json",
    ),
    (
        ROOT / "docs/assets/schemas/invite-options.schema.json",
        ROOT / "docs/assets/examples/invite-options.example.json",
    ),
    (
        ROOT / "docs/assets/schemas/move-payload.schema.json",
        ROOT / "docs/assets/examples/move-payload.example.json",
    ),
]


def load_json(path: Path) -> dict:
    with path.open("r", encoding="utf-8") as handle:
        return json.load(handle)


def validate_pair(schema_path: Path, example_path: Path) -> list[str]:
    schema = load_json(schema_path)
    example = load_json(example_path)

    Draft202012Validator.check_schema(schema)
    validator = Draft202012Validator(schema)

    errors: list[str] = []
    for error in sorted(validator.iter_errors(example), key=lambda value: list(value.absolute_path)):
        path = ".".join(str(part) for part in error.absolute_path) or "<root>"
        errors.append(f"{example_path.name}: {path}: {error.message}")

    return errors


def main() -> int:
    all_errors: list[str] = []

    for schema_path, example_path in VALIDATION_PAIRS:
        all_errors.extend(validate_pair(schema_path, example_path))

    if all_errors:
        print("Schema validation failed:")
        for line in all_errors:
            print(f"- {line}")
        return 1

    print("All schema examples are valid.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
