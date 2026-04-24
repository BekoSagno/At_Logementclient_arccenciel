# Script de configuration de l'email Mailtrap pour AT Logement
# Exécutez ce script pour configurer automatiquement le .env

$envFile = ".env"

# Vérifier si le fichier .env existe
if (-not (Test-Path $envFile)) {
    Write-Host "[ERREUR] Le fichier .env n'existe pas !" -ForegroundColor Red
    Write-Host "Créez d'abord le fichier .env avec: copy .env.example .env" -ForegroundColor Yellow
    exit 1
}

Write-Host "`n[INFO] Configuration de l'email Mailtrap..." -ForegroundColor Cyan

# Lire le contenu actuel du .env
$content = Get-Content $envFile -Raw

# Configuration Mailtrap SMTP
$mailConfig = @"
# --- EMAIL (MAILTRAP SMTP) ---
MAIL_MAILER=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=api
MAIL_PASSWORD=c4fc832f4b557817f8aeee6035d7b993
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@arccenciel.com
MAIL_FROM_NAME="AT_LOGEMENT"

# Email admin pour recevoir les notifications
ADMIN_EMAIL=admin@at-logement.com
"@

# Supprimer les anciennes configurations email si elles existent
$content = $content -replace "(?m)^MAIL_MAILER=.*$", ""
$content = $content -replace "(?m)^MAIL_HOST=.*$", ""
$content = $content -replace "(?m)^MAIL_PORT=.*$", ""
$content = $content -replace "(?m)^MAIL_USERNAME=.*$", ""
$content = $content -replace "(?m)^MAIL_PASSWORD=.*$", ""
$content = $content -replace "(?m)^MAIL_ENCRYPTION=.*$", ""
$content = $content -replace "(?m)^MAIL_FROM_ADDRESS=.*$", ""
$content = $content -replace "(?m)^MAIL_FROM_NAME=.*$", ""
$content = $content -replace "(?m)^ADMIN_EMAIL=.*$", ""
$content = $content -replace "(?m)^# --- EMAIL.*$", ""

# Nettoyer les lignes vides multiples
$content = $content -replace "(?m)^\s*$\r?\n", ""

# Ajouter la nouvelle configuration
if ($content -notmatch "MAIL_MAILER") {
    # Ajouter à la fin du fichier
    $content += "`n`n$mailConfig"
} else {
    # Remplacer si déjà présent
    $content = $content -replace "(?s)(# --- EMAIL.*?ADMIN_EMAIL=.*?`n)", $mailConfig
}

# Écrire le fichier
Set-Content -Path $envFile -Value $content -NoNewline

Write-Host "[SUCCÈS] Configuration email ajoutée au fichier .env !" -ForegroundColor Green
Write-Host "`nConfiguration appliquée :" -ForegroundColor Cyan
Write-Host "  - MAIL_MAILER: smtp" -ForegroundColor White
Write-Host "  - MAIL_HOST: live.smtp.mailtrap.io" -ForegroundColor White
Write-Host "  - MAIL_PORT: 587" -ForegroundColor White
Write-Host "  - MAIL_USERNAME: api" -ForegroundColor White
Write-Host "  - MAIL_FROM_ADDRESS: noreply@arccenciel.com" -ForegroundColor White
Write-Host "  - MAIL_FROM_NAME: AT_LOGEMENT" -ForegroundColor White
Write-Host "  - ADMIN_EMAIL: admin@at-logement.com" -ForegroundColor White
Write-Host "`n[INFO] N'oubliez pas de modifier ADMIN_EMAIL avec l'email de l'administrateur !" -ForegroundColor Yellow
Write-Host "[INFO] Exécutez: php artisan config:clear pour appliquer les changements" -ForegroundColor Yellow
