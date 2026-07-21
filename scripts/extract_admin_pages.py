#!/usr/bin/env python3
"""Extract admin.php content-switch cases into modular page files."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
ADMIN = ROOT / 'unified_portal' / 'admin' / 'admin.php'
PAGES = ROOT / 'unified_portal' / 'admin' / 'pages'

EXTRACTIONS = [
    ('dashboard.php', "case 'dashboard':"),
    ('certificates_referrals.php', "case 'certificates':"),
    ('settings.php', "case 'settings':"),
    ('admin_management.php', "case 'admin_management':"),
    ('user_logs.php', "case 'user_logs':"),
    ('health_dental_records.php', "case 'dental_records':"),
    ('view_record.php', "case 'view_record':"),
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

    content_start = start
    while content_start + 1 < len(lines) and CASE_PATTERN.match(lines[content_start + 1]):
        content_start += 1
    content_start += 1

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


def main() -> None:
    lines = ADMIN.read_text(encoding='utf-8').splitlines(keepends=True)
    switch_start = content_switch_start(lines)
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
        print(f'Wrote {filename}: lines {start + 1}-{end + 1} ({end - start + 1} lines)')


if __name__ == '__main__':
    main()
