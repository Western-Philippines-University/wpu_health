#!/usr/bin/env python3
"""Extract settings, admin_management, user_logs into page modules and wire router."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
ADMIN = ROOT / 'unified_portal' / 'admin' / 'admin.php'
PAGES = ROOT / 'unified_portal' / 'admin' / 'pages'

EXTRACTIONS = [
    ('settings.php', "case 'settings':"),
    ('admin_management.php', "case 'admin_management':"),
    ('user_logs.php', "case 'user_logs':"),
]

CASE_PATTERN = re.compile(r"^                        case '[^']+':")


def content_switch_start(lines: list[str]) -> int:
    for i, line in enumerate(lines):
        if line.strip() == 'switch($page) {':
            return i
    raise RuntimeError('switch($page) not found')


def find_case_block(lines: list[str], switch_start: int, case_needle: str) -> tuple[int, int]:
    start = None
    for i in range(switch_start, len(lines)):
        if lines[i].strip() == case_needle.strip():
            start = i
            break
    if start is None:
        raise RuntimeError(f'Case not found: {case_needle}')

    content_start = start + 1
    break_idx = None
    for i in range(content_start, len(lines)):
        if lines[i].strip() == 'break;':
            break_idx = i
            break
    if break_idx is None:
        raise RuntimeError(f'break not found for {case_needle}')

    content_end = break_idx - 1
    while content_end >= content_start and lines[content_end].strip() in ('<?php', ''):
        content_end -= 1

    return content_start, content_end


def extract_pages(lines: list[str], switch_start: int) -> None:
    PAGES.mkdir(parents=True, exist_ok=True)
    for filename, needle in EXTRACTIONS:
        start, end = find_case_block(lines, switch_start, needle)
        chunk = ''.join(lines[start : end + 1])
        header = (
            '<?php\n'
            f'/**\n * Modular page: {filename.replace(".php", "")}\n'
            ' * Included by wpu_page_router.php — do not access directly.\n */\n'
        )
        (PAGES / filename).write_text(header + chunk, encoding='utf-8')
        print(f'Wrote {filename}: lines {start + 1}-{end + 1}')


def wire_router(content: str) -> str:
    replacements = [
        (
            r"                        case 'settings':.*?                            break;\n                        case 'admin_management':",
            "                        case 'settings':\n"
            "                            wpu_render_admin_page('settings');\n"
            "                            break;\n"
            "                        case 'admin_management':",
        ),
        (
            r"                        case 'admin_management':.*?                            break;\n                        case 'user_logs':",
            "                        case 'admin_management':\n"
            "                            wpu_render_admin_page('admin_management');\n"
            "                            break;\n"
            "                        case 'user_logs':",
        ),
        (
            r"                        case 'user_logs':.*?                            break;\n                        case 'dental_records':",
            "                        case 'user_logs':\n"
            "                            wpu_render_admin_page('user_logs');\n"
            "                            break;\n"
            "                        case 'dental_records':",
        ),
    ]
    for pattern, repl in replacements:
        content, count = re.subn(pattern, repl, content, count=1, flags=re.DOTALL)
        if count != 1:
            raise RuntimeError(f'Wire failed for: {pattern[:50]!r}')
    return content


def main() -> None:
    text = ADMIN.read_text(encoding='utf-8')
    lines = text.splitlines(keepends=True)
    switch_start = content_switch_start(lines)

    extract_pages(lines, switch_start)

    before = len(lines)
    new_text = wire_router(text)
    ADMIN.write_text(new_text, encoding='utf-8')
    after = len(new_text.splitlines())
    print(f'admin.php: {before} -> {after} lines ({before - after} removed)')


if __name__ == '__main__':
    main()
