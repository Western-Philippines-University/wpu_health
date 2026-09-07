import re

path = r'unified_portal/admin/admin.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

reports_pattern = r"                        case 'reports':\n.*?                            break;\n                        case 'backup':"
reports_repl = "                        case 'reports':\n                            wpu_render_admin_page('reports');\n                            break;\n                        case 'backup':"

backup_pattern = r"                        case 'backup':\n                            \?>\n                            <div class=\"content-card\">.*?                            break;\n                        default:"
backup_repl = "                        case 'backup':\n                            wpu_render_admin_page('backup');\n                            break;\n                        default:"

new_content, n1 = re.subn(reports_pattern, reports_repl, content, count=1, flags=re.DOTALL)
if n1 != 1:
    raise SystemExit(f'reports replace failed: {n1}')

new_content, n2 = re.subn(backup_pattern, backup_repl, new_content, count=1, flags=re.DOTALL)
if n2 != 1:
    raise SystemExit(f'backup replace failed: {n2}')

with open(path, 'w', encoding='utf-8', newline='') as f:
    f.write(new_content)

print('Replaced reports and backup cases')
