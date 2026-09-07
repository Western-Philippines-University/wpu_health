$ErrorActionPreference = "Stop"
$root = Split-Path $PSScriptRoot -Parent
$dist = Join-Path $root "netlify-dist"
$port = 8765
$backendUrl = $env:ADMIN_BACKEND_URL

$null = New-Item -ItemType Directory -Force -Path $dist

$server = Start-Process -FilePath "php" -ArgumentList "artisan serve --host=127.0.0.1 --port=$port" -WorkingDirectory $root -PassThru -WindowStyle Hidden
Start-Sleep -Seconds 3

try {
    $html = (Invoke-WebRequest -Uri "http://127.0.0.1:$port/" -UseBasicParsing).Content
    $html = $html -replace 'http://localhost/wpu_medical-master/admin/login', '/admin/login'
    $html = $html -replace "http://127.0.0.1:$port/admin/login", '/admin/login'
    Set-Content -Path (Join-Path $dist "index.html") -Value $html -Encoding utf8

    if ($backendUrl) {
        @(
            "/admin/*  $backendUrl/admin/:splat  200!"
            "/portal/*  $backendUrl/portal/:splat  200!"
            "/up  $backendUrl/up  200!"
        ) | Set-Content -Path (Join-Path $dist "_redirects") -Encoding utf8
        Remove-Item -Path (Join-Path $dist "admin\index.html") -ErrorAction SilentlyContinue
        Write-Host "Admin routes will proxy to $backendUrl"
    } else {
        Remove-Item -Path (Join-Path $dist "_redirects") -ErrorAction SilentlyContinue
        Write-Host "ADMIN_BACKEND_URL not set - /admin shows placeholder until backend is configured."
    }

    Write-Host "Exported landing page to netlify-dist/index.html"
}
finally {
    Stop-Process -Id $server.Id -Force -ErrorAction SilentlyContinue
}
