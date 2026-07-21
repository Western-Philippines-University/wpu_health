#!/usr/bin/env python3
"""Extract inline admin.php JavaScript to admin-core.js."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
ADMIN = ROOT / 'unified_portal' / 'admin' / 'admin.php'
JS_OUT = ROOT / 'unified_portal' / 'assets' / 'js' / 'admin-core.js'

PHP_IDLE_ENABLED = re.compile(
    r"^\s*enabled:\s*<\?php echo \$enabled \? 'true' : 'false'; \?>,\s*$",
    re.MULTILINE,
)
PHP_IDLE_TIMEOUT = re.compile(
    r"^\s*timeout:\s*<\?php echo \$timeout; \?>,?\s*(// in milliseconds)?\s*$",
    re.MULTILINE,
)

IDLE_REPLACEMENT = """    enabled: !!(window.WPU_HIS_CONFIG && window.WPU_HIS_CONFIG.autoLock && window.WPU_HIS_CONFIG.autoLock.enabled),
    timeout: (window.WPU_HIS_CONFIG && window.WPU_HIS_CONFIG.autoLock && window.WPU_HIS_CONFIG.autoLock.timeout) || 300000,"""


def extract_js_block(content: str) -> tuple[str, str]:
    match = re.search(r"\n <script>\n(.*?)\n</script>\n\n        <!-- Dental Add Record Modal -->", content, re.DOTALL)
    if not match:
        raise RuntimeError('Main script block not found')
    js = match.group(1)
    js = PHP_IDLE_ENABLED.sub(IDLE_REPLACEMENT.split('\n')[0], js, count=1)
    js = PHP_IDLE_TIMEOUT.sub(IDLE_REPLACEMENT.split('\n')[1], js, count=1)
    return js, match.group(0)


def main() -> None:
    content = ADMIN.read_text(encoding='utf-8')
    js, block = extract_js_block(content)

    header = (
        '/**\n'
        ' * WPU Medical Admin — core application JavaScript\n'
        ' * Extracted from admin.php for browser caching.\n'
        ' * Boot config: window.WPU_HIS_CONFIG (autoLock.enabled, autoLock.timeout)\n'
        ' * Must stay in global scope for inline onclick handlers.\n'
        ' */\n\n'
    )
    footer = '\n'

    JS_OUT.parent.mkdir(parents=True, exist_ok=True)
    JS_OUT.write_text(header + js + footer, encoding='utf-8')

    replacement = (
        '\n        <!-- Admin core JS loaded at end of body -->\n\n'
        '        <!-- Dental Add Record Modal -->'
    )
    new_content = content.replace(block, replacement, 1)

    # Add filemtime variable near other asset versions
    if '$his_core_js_v' not in new_content:
        new_content = new_content.replace(
            "$his_ajax_js_v = @filemtime(__DIR__.'/../assets/js/wpu-ajax.js') ?: time();",
            "$his_ajax_js_v = @filemtime(__DIR__.'/../assets/js/wpu-ajax.js') ?: time();\n"
            "    $his_core_js_v = @filemtime(__DIR__.'/../assets/js/admin-core.js') ?: time();",
            1,
        )

    config_and_script = (
        '<script>\n'
        'window.WPU_HIS_CONFIG = <?php echo json_encode([\n'
        "    'autoLock' => ['enabled' => (bool) $enabled, 'timeout' => (int) $timeout],\n"
        '], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;\n'
        '</script>\n'
        '<script src="<?php echo htmlspecialchars($his_assets, ENT_QUOTES, \'UTF-8\'); ?>/js/admin-core.js?v=<?php echo (int) $his_core_js_v; ?>"></script>\n'
    )

    anchor = '<script src="<?php echo htmlspecialchars($his_assets, ENT_QUOTES, \'UTF-8\'); ?>/js/wpu-ajax.js'
    if anchor not in new_content:
        raise RuntimeError('Script anchor not found')
    new_content = new_content.replace(anchor, config_and_script + anchor, 1)

    ADMIN.write_text(new_content, encoding='utf-8')
    lines = js.count('\n') + 1
    print(f'Wrote admin-core.js ({lines} lines, {JS_OUT.stat().st_size} bytes)')
    print(f'admin.php: {len(content.splitlines())} -> {len(new_content.splitlines())} lines')


if __name__ == '__main__':
    main()
