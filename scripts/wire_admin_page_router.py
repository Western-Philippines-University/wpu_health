#!/usr/bin/env python3
"""Replace extracted switch cases in admin.php with wpu_render_admin_page calls."""

from __future__ import annotations

import re
from pathlib import Path

ADMIN = Path(__file__).resolve().parent.parent / 'unified_portal' / 'admin' / 'admin.php'

REPLACEMENTS = [
    (
        r"                        case 'dashboard':.*?                            break;\n",
        "                        case 'dashboard':\n"
        "                            wpu_render_admin_page('dashboard');\n"
        "                            break;\n",
    ),
    (
        r"                        case 'certificates':\n"
        r"                        case 'referrals':\n"
        r"                        case 'certificates_referrals':.*?                            break;\n",
        "                        case 'certificates':\n"
        "                        case 'referrals':\n"
        "                        case 'certificates_referrals':\n"
        "                            wpu_render_admin_page($page);\n"
        "                            break;\n",
    ),
    (
        r"                        case 'dental_records':\n"
        r"                        case 'health_records':\n"
        r"                        case 'health_dental_records':.*?                            break;\n",
        "                        case 'dental_records':\n"
        "                        case 'health_records':\n"
        "                        case 'health_dental_records':\n"
        "                            wpu_render_admin_page($page);\n"
        "                            break;\n",
    ),
    (
        r"                        case 'view_record':.*?                            break;\n",
        "                        case 'view_record':\n"
        "                            wpu_render_admin_page('view_record');\n"
        "                            break;\n",
    ),
]


def main() -> None:
    content = ADMIN.read_text(encoding='utf-8')
    original_len = len(content.splitlines())

    for pattern, repl in REPLACEMENTS:
        new_content, count = re.subn(pattern, repl, content, count=1, flags=re.DOTALL)
        if count != 1:
            raise SystemExit(f'Replacement failed for pattern starting with: {pattern[:60]!r} (count={count})')
        content = new_content

    ADMIN.write_text(content, encoding='utf-8')
    new_len = len(content.splitlines())
    print(f'admin.php: {original_len} -> {new_len} lines ({original_len - new_len} removed)')


if __name__ == '__main__':
    main()
