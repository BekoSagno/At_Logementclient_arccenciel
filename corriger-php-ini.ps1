# Script pour corriger automatiquement le fichier php.ini
# Active les extensions gd, intl et zip

$phpIniPath = "C:\xampp\php\php.ini"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Correction du fichier php.ini" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verifier si le fichier existe
if (-not (Test-Path $phpIniPath)) {
    Write-Host "[X] Fichier php.ini non trouve : $phpIniPath" -ForegroundColor Red
    Write-Host "    Verifiez le chemin avec : php --ini" -ForegroundColor Yellow
    exit 1
}

Write-Host "[OK] Fichier trouve : $phpIniPath" -ForegroundColor Green
Write-Host ""

# Faire une sauvegarde
$backupPath = "$phpIniPath.backup.$(Get-Date -Format 'yyyyMMdd_HHmmss')"
Write-Host "Creation d'une sauvegarde..." -ForegroundColor Yellow
Copy-Item $phpIniPath $backupPath
Write-Host "[OK] Sauvegarde creee : $backupPath" -ForegroundColor Green
Write-Host ""

# Lire le contenu du fichier
Write-Host "Lecture du fichier..." -ForegroundColor Yellow
$content = Get-Content $phpIniPath -Raw

# Compter les modifications
$modifications = 0

# Correction 1 : extension=gd
if ($content -match ";extension=gd") {
    $content = $content -replace ";extension=gd", "extension=gd"
    Write-Host "[OK] Extension GD activee" -ForegroundColor Green
    $modifications++
} elseif ($content -match "^extension=gd") {
    Write-Host "[OK] Extension GD deja activee" -ForegroundColor Yellow
} else {
    Write-Host "[!] Extension GD non trouvee" -ForegroundColor Red
}

# Correction 2 : extension=intl
if ($content -match ";extension=intl") {
    $content = $content -replace ";extension=intl", "extension=intl"
    Write-Host "[OK] Extension INTL activee" -ForegroundColor Green
    $modifications++
} elseif ($content -match "^extension=intl") {
    Write-Host "[OK] Extension INTL deja activee" -ForegroundColor Yellow
} else {
    Write-Host "[!] Extension INTL non trouvee" -ForegroundColor Red
}

# Correction 3 : extension=zip
if ($content -match ";extension=zip") {
    $content = $content -replace ";extension=zip", "extension=zip"
    Write-Host "[OK] Extension ZIP activee" -ForegroundColor Green
    $modifications++
} elseif ($content -match "^extension=zip") {
    Write-Host "[OK] Extension ZIP deja activee" -ForegroundColor Yellow
} else {
    Write-Host "[!] Extension ZIP non trouvee" -ForegroundColor Red
}

# Sauvegarder les modifications
if ($modifications -gt 0) {
    Write-Host ""
    Write-Host "Sauvegarde des modifications..." -ForegroundColor Yellow
    Set-Content -Path $phpIniPath -Value $content -NoNewline
    Write-Host "[OK] $modifications modification(s) appliquee(s)" -ForegroundColor Green
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  Modifications terminees !" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "PROCHAINES ETAPES :" -ForegroundColor Yellow
    Write-Host "1. Redemarrez Apache dans XAMPP" -ForegroundColor White
    Write-Host "2. Verifiez avec : php -m | findstr `"gd intl zip`"" -ForegroundColor White
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "[OK] Toutes les extensions sont deja activees" -ForegroundColor Green
    Write-Host ""
}

Write-Host "Sauvegarde disponible a : $backupPath" -ForegroundColor Cyan
Write-Host ""
