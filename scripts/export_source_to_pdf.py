#!/usr/bin/env python3
"""
Export WPU Medical project source code to a single PDF document.
Excludes vendor/, node_modules/, storage caches, and sensitive files (.env).
"""

from __future__ import annotations

import argparse
import sys
from datetime import datetime
from pathlib import Path

from fpdf import FPDF

PROJECT_ROOT = Path(__file__).resolve().parent.parent

SOURCE_EXTENSIONS = {
    ".php",
    ".js",
    ".css",
    ".sql",
    ".json",
    ".md",
    ".xml",
    ".blade.php",
    ".env.example",
    ".htaccess",
}

INCLUDE_ROOT_FILES = {
    "composer.json",
    "composer.lock",
    "package.json",
    "vite.config.js",
    "phpunit.xml",
    "README.md",
    "artisan",
    "index.php",
    ".htaccess",
    ".env.example",
    ".editorconfig",
    ".gitattributes",
    ".gitignore",
}

SOURCE_DIRS = [
    "app",
    "bootstrap",
    "config",
    "database",
    "public",
    "resources",
    "routes",
    "tests",
    "unified_portal",
    "scripts",
]

EXCLUDE_DIR_NAMES = {
    "vendor",
    "node_modules",
    "storage",
    ".git",
    ".idea",
    ".vscode",
    "cache",
    "build",
    "hot",
}

EXCLUDE_FILE_NAMES = {
    ".env",
    ".env.backup",
    ".env.production",
    "auth.json",
}

MAX_LINE_LENGTH = 120


def is_source_file(path: Path) -> bool:
    name = path.name
    if name in EXCLUDE_FILE_NAMES:
        return False
    if name in INCLUDE_ROOT_FILES:
        return True
    if name.endswith(".blade.php"):
        return True
    return path.suffix.lower() in SOURCE_EXTENSIONS


def should_skip_dir(path: Path) -> bool:
    return path.name in EXCLUDE_DIR_NAMES


def collect_files(root: Path) -> list[Path]:
    files: list[Path] = []

    for rel in sorted(INCLUDE_ROOT_FILES):
        candidate = root / rel
        if candidate.is_file() and is_source_file(candidate):
            files.append(candidate)

    for dir_name in SOURCE_DIRS:
        base = root / dir_name
        if not base.is_dir():
            continue
        for path in sorted(base.rglob("*")):
            if path.is_dir():
                continue
            if any(part in EXCLUDE_DIR_NAMES for part in path.parts):
                continue
            if is_source_file(path):
                files.append(path)

    seen: set[Path] = set()
    unique: list[Path] = []
    for path in files:
        resolved = path.resolve()
        if resolved not in seen:
            seen.add(resolved)
            unique.append(path)
    return unique


def sanitize_line(line: str) -> str:
    line = line.replace("\t", "    ")
    line = line.rstrip("\r\n")
    if len(line) > MAX_LINE_LENGTH:
        line = line[: MAX_LINE_LENGTH - 3] + "..."
    return line


def safe_text(text: str) -> str:
    text = text.replace("\u2014", "-").replace("\u2013", "-").replace("\u2018", "'")
    text = text.replace("\u2019", "'").replace("\u201c", '"').replace("\u201d", '"')
    return text.encode("latin-1", errors="replace").decode("latin-1")


class SourcePDF(FPDF):
    def __init__(self, project_name: str) -> None:
        super().__init__(orientation="P", unit="mm", format="A4")
        self.project_name = project_name
        self.set_auto_page_break(auto=True, margin=15)
        self.set_margins(15, 15, 15)
        self.content_width = 210 - 15 - 15  # A4 width minus margins

    def header(self) -> None:
        self.set_font("Courier", "B", 8)
        self.set_text_color(100, 100, 100)
        self.cell(self.content_width, 5, safe_text(self.project_name), align="L")
        self.ln(6)

    def footer(self) -> None:
        self.set_y(-12)
        self.set_font("Courier", "", 8)
        self.set_text_color(100, 100, 100)
        self.cell(self.content_width, 5, safe_text(f"Page {self.page_no()}"), align="C")

    def write_line(self, height: float, text: str) -> None:
        if self.get_y() + height > self.page_break_trigger:
            self.add_page()
        self.set_x(self.l_margin)
        self.multi_cell(self.content_width, height, safe_text(text))


def build_pdf(files: list[Path], output: Path, root: Path) -> None:
    pdf = SourcePDF(project_name="WPU Medical - Source Code Export")
    pdf.add_page()
    w = pdf.content_width

    pdf.set_font("Courier", "B", 16)
    pdf.set_text_color(0, 0, 0)
    pdf.multi_cell(w, 8, safe_text("WPU Medical - Source Code Export"))
    pdf.ln(2)

    pdf.set_font("Courier", "", 9)
    pdf.set_text_color(60, 60, 60)
    pdf.multi_cell(
        w,
        5,
        safe_text(
            f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n"
            f"Project root: {root}\n"
            f"Files included: {len(files)}"
        ),
    )
    pdf.ln(4)

    pdf.set_font("Courier", "B", 10)
    pdf.set_text_color(0, 0, 0)
    pdf.multi_cell(w, 5, safe_text("Table of Contents"))
    pdf.ln(1)

    pdf.set_font("Courier", "", 8)
    for index, path in enumerate(files, start=1):
        rel = path.relative_to(root).as_posix()
        pdf.write_line(4, f"{index:3}. {rel}")
    pdf.ln(4)

    for index, path in enumerate(files, start=1):
        rel = path.relative_to(root).as_posix()
        pdf.add_page()

        pdf.set_font("Courier", "B", 11)
        pdf.set_text_color(0, 51, 102)
        pdf.multi_cell(w, 6, safe_text(f"File {index}/{len(files)}: {rel}"))
        pdf.ln(1)

        try:
            content = path.read_text(encoding="utf-8")
        except UnicodeDecodeError:
            content = path.read_text(encoding="latin-1", errors="replace")
        except OSError as exc:
            pdf.set_font("Courier", "I", 9)
            pdf.set_text_color(180, 0, 0)
            pdf.multi_cell(w, 5, safe_text(f"[Could not read file: {exc}]"))
            continue

        pdf.set_font("Courier", "", 7)
        pdf.set_text_color(0, 0, 0)

        if not content.strip():
            pdf.multi_cell(w, 4, safe_text("(empty file)"))
            continue

        for line in content.splitlines():
            pdf.write_line(3.2, sanitize_line(line))

    output.parent.mkdir(parents=True, exist_ok=True)
    pdf.output(str(output))


def main() -> int:
    parser = argparse.ArgumentParser(description="Export project source code to PDF")
    parser.add_argument(
        "-o",
        "--output",
        type=Path,
        default=PROJECT_ROOT / "exports" / "wpu_medical_source_code.pdf",
        help="Output PDF path",
    )
    args = parser.parse_args()

    files = collect_files(PROJECT_ROOT)
    if not files:
        print("No source files found.", file=sys.stderr)
        return 1

    build_pdf(files, args.output, PROJECT_ROOT)
    print(f"Exported {len(files)} files to: {args.output}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
