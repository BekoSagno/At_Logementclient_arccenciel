# Script de Préparation du Projet AT Logement
# Ce script vérifie et prépare l'environnement pour éviter les erreurs

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Préparation du Projet AT Logement" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Fonction pour afficher les messages
function Show-Status {
    param($message, $status)
    if ($status -eq "OK") {
        Write-Host "[OK] $message" -ForegroundColor Green
    } elseif ($status -eq "WARN") {
        Write-Host "[!] $message" -ForegroundColor Yellow
    } else {
        Write-Host "[X] $message" -ForegroundColor Red
    }
}

# 1. Vérification PHP
Write-Host "1. Vérification de PHP..." -ForegroundColor Yellow
try {
    $phpVersion = php -v 2>&1 | Select-String -Pattern "PHP (\d+\.\d+)" | ForEach-Object { $_.Matches.Groups[1].Value }
    if ([version]$phpVersion -ge [version]"8.2") {
        Show-Status "PHP $phpVersion installe" "OK"
    } else {
        Show-Status "PHP $phpVersion installe (version 8.2 ou superieure requise)" "WARN"
    }
} catch {
    Show-Status "PHP non trouvé" "ERROR"
    exit 1
}

# 2. Vérification des extensions PHP
Write-Host "`n2. Vérification des extensions PHP..." -ForegroundColor Yellow
$requiredExtensions = @("pdo", "pdo_sqlite", "mbstring", "xml", "ctype", "json", "openssl", "tokenizer", "fileinfo")
$recommendedExtensions = @("gd", "intl", "zip")

$missingRequired = @()
$missingRecommended = @()

foreach ($ext in $requiredExtensions) {
    $result = php -m 2>&1 | Select-String -Pattern "^$ext$"
    if ($result) {
        Show-Status "Extension $ext activee" "OK"
    } else {
        Show-Status "Extension $ext manquante" "ERROR"
        $missingRequired += $ext
    }
}

foreach ($ext in $recommendedExtensions) {
    $result = php -m 2>&1 | Select-String -Pattern "^$ext$"
    if ($result) {
        Show-Status "Extension $ext activee" "OK"
    } else {
        Show-Status "Extension $ext manquante (recommandee)" "WARN"
        $missingRecommended += $ext
    }
}

if ($missingRequired.Count -gt 0) {
    Write-Host "`n[!] Extensions PHP obligatoires manquantes !" -ForegroundColor Red
    Write-Host "   Activez-les dans votre php.ini" -ForegroundColor Yellow
    exit 1
}

# 3. Vérification Composer
Write-Host "`n3. Vérification de Composer..." -ForegroundColor Yellow
try {
    $composerVersion = composer --version 2>&1 | Select-String -Pattern "Composer version (\d+\.\d+)" | ForEach-Object { $_.Matches.Groups[1].Value }
    Show-Status "Composer $composerVersion installe" "OK"
} catch {
    Show-Status "Composer non trouvé" "ERROR"
    exit 1
}

# 4. Vérification Node.js et NPM
Write-Host "`n4. Vérification de Node.js et NPM..." -ForegroundColor Yellow
try {
    $nodeVersion = node --version
    Show-Status "Node.js $nodeVersion installe" "OK"
} catch {
    Show-Status "Node.js non trouvé" "ERROR"
    exit 1
}

try {
    $npmVersion = npm --version
    Show-Status "NPM $npmVersion installe" "OK"
} catch {
    Show-Status "NPM non trouvé" "ERROR"
    exit 1
}

# 5. Vérification du fichier .env
Write-Host "`n5. Vérification de la configuration..." -ForegroundColor Yellow
if (Test-Path ".env") {
    Show-Status "Fichier .env trouvé" "OK"
} else {
    Show-Status "Fichier .env manquant" "WARN"
    if (Test-Path ".env.example") {
        Write-Host "   Copie de .env.example vers .env..." -ForegroundColor Yellow
        Copy-Item ".env.example" ".env"
        Show-Status "Fichier .env cree" "OK"
    } else {
        Write-Host "   [!] Creez manuellement le fichier .env" -ForegroundColor Red
    }
}

# 6. Installation des dépendances Composer
Write-Host "`n6. Installation des dépendances PHP (Composer)..." -ForegroundColor Yellow
if (Test-Path "vendor") {
    Write-Host "   Dépendances déjà installées, mise à jour..." -ForegroundColor Yellow
    composer update --no-interaction 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Show-Status "Dependances Composer a jour" "OK"
    } else {
        Show-Status "Erreur lors de la mise à jour Composer" "ERROR"
    }
} else {
    Write-Host "   Installation des dépendances..." -ForegroundColor Yellow
    composer install --no-interaction 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Show-Status "Dependances Composer installees" "OK"
    } else {
        Show-Status "Erreur lors de l'installation Composer" "ERROR"
    }
}

# 7. Installation des dépendances NPM
Write-Host "`n7. Installation des dépendances JavaScript (NPM)..." -ForegroundColor Yellow
if (Test-Path "node_modules") {
    Write-Host "   Dépendances déjà installées, mise à jour..." -ForegroundColor Yellow
    npm update 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Show-Status "Dependances NPM a jour" "OK"
    } else {
        Show-Status "Erreur lors de la mise à jour NPM" "ERROR"
    }
} else {
    Write-Host "   Installation des dépendances..." -ForegroundColor Yellow
    npm install 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Show-Status "Dependances NPM installees" "OK"
    } else {
        Show-Status "Erreur lors de l'installation NPM" "ERROR"
    }
}

# 8. Génération de la clé d'application
Write-Host "`n8. Génération de la clé d'application..." -ForegroundColor Yellow
if (Test-Path ".env") {
    $envContent = Get-Content ".env" -Raw
    if ($envContent -notmatch "APP_KEY=base64:") {
        php artisan key:generate --no-interaction 2>&1 | Out-Null
        Show-Status "Cle d'application generee" "OK"
    } else {
        Show-Status "Cle d'application deja configuree" "OK"
    }
}

# 9. Base de données
Write-Host "`n9. Configuration de la base de données..." -ForegroundColor Yellow
if (Test-Path "database\database.sqlite") {
    Show-Status "Base de donnees SQLite trouvee" "OK"
} else {
    Write-Host "   Création de la base de données SQLite..." -ForegroundColor Yellow
    New-Item -ItemType File -Path "database\database.sqlite" -Force | Out-Null
        Show-Status "Base de donnees SQLite creee" "OK"
}

# 10. Migrations
Write-Host "`n10. Exécution des migrations..." -ForegroundColor Yellow
php artisan migrate --force 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Show-Status "Migrations executees" "OK"
} else {
    Show-Status "Erreur lors des migrations" "WARN"
}

# 11. Utilisateur admin
Write-Host "`n11. Création de l'utilisateur admin..." -ForegroundColor Yellow
php artisan db:seed --class=AdminUserSeeder --force 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Show-Status "Utilisateur admin cree" "OK"
} else {
        Show-Status "Utilisateur admin deja existant ou erreur" "WARN"
}

# 12. Lien symbolique storage
Write-Host "`n12. Création du lien symbolique storage..." -ForegroundColor Yellow
php artisan storage:link 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Show-Status "Lien symbolique cree" "OK"
} else {
        Show-Status "Lien symbolique deja existant ou erreur" "WARN"
}

# 13. Nettoyage du cache
Write-Host "`n13. Nettoyage du cache..." -ForegroundColor Yellow
php artisan config:clear 2>&1 | Out-Null
php artisan cache:clear 2>&1 | Out-Null
php artisan route:clear 2>&1 | Out-Null
php artisan view:clear 2>&1 | Out-Null
    Show-Status "Cache nettoye" "OK"

# Résumé
Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  Résumé de la Préparation" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "[OK] Environnement prepare avec succes !" -ForegroundColor Green
Write-Host ""
Write-Host "Prochaines etapes :" -ForegroundColor Yellow
Write-Host "   1. Lancer le serveur : php artisan serve" -ForegroundColor White
Write-Host "   2. Lancer Vite (dans un autre terminal) : npm run dev" -ForegroundColor White
Write-Host "   3. Acceder au site : http://localhost:8000" -ForegroundColor White
Write-Host "   4. Acceder au panel admin : http://localhost:8000/admin" -ForegroundColor White
Write-Host "      - Email : admin@at-logement.com" -ForegroundColor White
Write-Host "      - Mot de passe : password" -ForegroundColor White
Write-Host ""

if ($missingRecommended.Count -gt 0) {
    Write-Host "[!] Extensions recommandees manquantes :" -ForegroundColor Yellow
    foreach ($ext in $missingRecommended) {
        Write-Host "   - $ext (activez dans php.ini)" -ForegroundColor Yellow
    }
    Write-Host ""
}

Write-Host "Consultez SETUP_GUIDE.md pour plus de details" -ForegroundColor Cyan
Write-Host ""
