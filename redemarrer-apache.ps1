# Script pour redémarrer Apache dans XAMPP
# Ce script redémarre Apache pour que les modifications de php.ini prennent effet

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Redémarrage d'Apache (XAMPP)" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Chemin vers le contrôleur XAMPP
$xamppControl = "C:\xampp\xampp-control.exe"
$apacheService = "Apache2.4"

# Vérifier si XAMPP est installé
if (-not (Test-Path $xamppControl)) {
    Write-Host "[ERREUR] XAMPP non trouvé à : $xamppControl" -ForegroundColor Red
    Write-Host ""
    Write-Host "Veuillez redémarrer Apache manuellement :" -ForegroundColor Yellow
    Write-Host "1. Ouvrez XAMPP Control Panel" -ForegroundColor Yellow
    Write-Host "2. Cliquez sur 'Stop' pour Apache" -ForegroundColor Yellow
    Write-Host "3. Attendez quelques secondes" -ForegroundColor Yellow
    Write-Host "4. Cliquez sur 'Start' pour Apache" -ForegroundColor Yellow
    exit 1
}

Write-Host "[INFO] Recherche du service Apache..." -ForegroundColor Yellow

# Méthode 1 : Via le service Windows (si Apache est installé en tant que service)
$apacheServiceObj = Get-Service -Name $apacheService -ErrorAction SilentlyContinue
if ($apacheServiceObj) {
    Write-Host "[INFO] Service Apache trouvé : $apacheService" -ForegroundColor Green
    
    if ($apacheServiceObj.Status -eq 'Running') {
        Write-Host "[INFO] Arrêt d'Apache..." -ForegroundColor Yellow
        Stop-Service -Name $apacheService -Force
        Start-Sleep -Seconds 3
    }
    
    Write-Host "[INFO] Démarrage d'Apache..." -ForegroundColor Yellow
    Start-Service -Name $apacheService
    Start-Sleep -Seconds 3
    
    if ((Get-Service -Name $apacheService).Status -eq 'Running') {
        Write-Host "[OK] Apache redémarré avec succès !" -ForegroundColor Green
    } else {
        Write-Host "[ERREUR] Impossible de démarrer Apache" -ForegroundColor Red
        Write-Host "[INFO] Veuillez redémarrer Apache manuellement via XAMPP Control Panel" -ForegroundColor Yellow
    }
} else {
    # Méthode 2 : Via XAMPP Control Panel (si Apache n'est pas un service Windows)
    Write-Host "[INFO] Service Apache non trouvé. Utilisation de XAMPP Control Panel..." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Veuillez redémarrer Apache manuellement :" -ForegroundColor Yellow
    Write-Host "1. Ouvrez XAMPP Control Panel (C:\xampp\xampp-control.exe)" -ForegroundColor Yellow
    Write-Host "2. Cliquez sur 'Stop' pour Apache" -ForegroundColor Yellow
    Write-Host "3. Attendez quelques secondes" -ForegroundColor Yellow
    Write-Host "4. Cliquez sur 'Start' pour Apache" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Ou exécutez ces commandes dans un terminal Administrateur :" -ForegroundColor Cyan
    Write-Host "  C:\xampp\apache_stop.bat" -ForegroundColor White
    Write-Host "  C:\xampp\apache_start.bat" -ForegroundColor White
}

Write-Host ""
Write-Host "Vérification de l'extension intl..." -ForegroundColor Yellow
Start-Sleep -Seconds 2

# Vérifier si intl est chargé
$intlCheck = php -m 2>&1 | Select-String -Pattern "^intl$"
if ($intlCheck) {
    Write-Host "[OK] Extension intl est chargée en CLI" -ForegroundColor Green
} else {
    Write-Host "[ERREUR] Extension intl n'est pas chargée" -ForegroundColor Red
    Write-Host "[INFO] Vérifiez que extension=intl est bien activée dans php.ini" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Redémarrage terminé" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "IMPORTANT : Si vous utilisez 'php artisan serve'," -ForegroundColor Yellow
Write-Host "vous devez aussi redémarrer le serveur Laravel :" -ForegroundColor Yellow
Write-Host "  1. Arrêtez le serveur (Ctrl+C)" -ForegroundColor White
Write-Host "  2. Relancez : php artisan serve" -ForegroundColor White
Write-Host ""
