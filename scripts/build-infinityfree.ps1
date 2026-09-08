$ErrorActionPreference = "Stop"

$root = Split-Path $PSScriptRoot -Parent
$staging = Join-Path $root "dist-infinityfree"
$zipPath = Join-Path $root "wpu-infinityfree.zip"
$php = "C:\xampp\php\php.exe"
if (-not (Test-Path $php)) {
    $php = "php"
}
$composerPhar = "C:\xampp\php\composer.phar"
if (-not (Test-Path $composerPhar)) {
    $composerPhar = "C:\xampp\htdocs\composer.phar"
}

Write-Host "Preparing InfinityFree package..."

if (Test-Path $staging) {
    Remove-Item -Recurse -Force $staging
}
if (Test-Path $zipPath) {
    Remove-Item -Force $zipPath
}

New-Item -ItemType Directory -Force -Path $staging | Out-Null

$excludeDirs = @(
    ".git", ".netlify", "node_modules", "netlify-dist", "dist-infinityfree",
    "tests", "vendor"
)
$excludeNames = @(
    ".env", "wpu-infinityfree.zip", "Homestead.json", "Homestead.yaml",
    "infinityfree-unzip.php"
)

Get-ChildItem -Force $root | ForEach-Object {
    if ($excludeNames -contains $_.Name) { return }
    if ($excludeDirs -contains $_.Name) { return }
    Copy-Item -Recurse -Force $_.FullName (Join-Path $staging $_.Name)
}

# Drop bulky generated/runtime files copied from local storage.
$runtimeGlobs = @(
    "storage\logs\*.log",
    "storage\framework\sessions\*",
    "storage\framework\views\*.php",
    "storage\framework\cache\data\*"
)
foreach ($glob in $runtimeGlobs) {
    Get-ChildItem -Force -ErrorAction SilentlyContinue (Join-Path $staging $glob) |
        Where-Object { $_.Name -ne ".gitignore" } |
        Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
}
Remove-Item -Recurse -Force -ErrorAction SilentlyContinue (Join-Path $staging "storage\framework\wpu_cache")

@(
    "storage\logs",
    "storage\framework\sessions",
    "storage\framework\views",
    "storage\framework\cache\data",
    "bootstrap\cache"
) | ForEach-Object {
    New-Item -ItemType Directory -Force -Path (Join-Path $staging $_) | Out-Null
}

if (-not (Test-Path (Join-Path $staging "public\build\manifest.json"))) {
    throw "public/build is missing. Run npm run build first."
}

Write-Host "Installing production Composer dependencies..."
Push-Location $staging
try {
    if (Test-Path $composerPhar) {
        & $php $composerPhar install --no-dev --prefer-dist --no-interaction --optimize-autoloader
    } else {
        composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
    }
    if ($LASTEXITCODE -ne 0) {
        throw "composer install --no-dev failed"
    }
} finally {
    Pop-Location
}

Write-Host "Creating zip..."
& $php (Join-Path $PSScriptRoot "zip-infinityfree.php") $staging $zipPath
if ($LASTEXITCODE -ne 0) {
    throw "zip creation failed"
}

$item = Get-Item $zipPath
Write-Host ("Package ready: {0} ({1:N1} MB)" -f $item.FullName, ($item.Length / 1MB))
Write-Host "Upload this zip plus infinityfree-unzip.php into InfinityFree htdocs."
