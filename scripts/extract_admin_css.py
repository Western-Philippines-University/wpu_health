import os

path = os.path.join(os.path.dirname(__file__), '..', 'unified_portal', 'admin', 'admin.php')
core = os.path.join(os.path.dirname(__file__), '..', 'unified_portal', 'assets', 'css', 'admin-core.css')

with open(path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

v = int(os.path.getmtime(core))
insert = [
    '    <link rel="preload" href="<?php echo htmlspecialchars($his_assets, ENT_QUOTES, \'UTF-8\'); ?>/css/admin-core.css?v=' + str(v) + '" as="style">\n',
    '    <link rel="stylesheet" href="<?php echo htmlspecialchars($his_assets, ENT_QUOTES, \'UTF-8\'); ?>/css/admin-core.css?v=' + str(v) + '">\n',
]

start = None
for i, line in enumerate(lines):
    if line.strip() == '<style>' and i > 500:
        start = i
        break

if start is None:
    raise SystemExit('style start not found')

end = None
for i in range(start, len(lines)):
    if lines[i].strip() == '</style>':
        end = i
        break

if end is None:
    raise SystemExit('style end not found')

print(f'Replacing lines {start + 1}-{end + 1} ({end - start + 1} lines)')
new_lines = lines[:start] + insert + lines[end + 1:]

with open(path, 'w', encoding='utf-8', newline='') as f:
    f.writelines(new_lines)

print(f'New line count: {len(new_lines)}')
