# Script de sauvegarde automatique de la base de données SQLite
# Usage: .\backup-database.ps1

$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupDir = "database\backups"
$dbFile = "database\database.sqlite"
$backupFile = "$backupDir\database_$timestamp.sqlite"

# Créer le dossier de backup s'il n'existe pas
if (-not (Test-Path $backupDir)) {
    New-Item -ItemType Directory -Path $backupDir -Force | Out-Null
    Write-Host "Dossier de backup créé : $backupDir" -ForegroundColor Green
}

# Vérifier que la base de données existe
if (Test-Path $dbFile) {
    # Copier le fichier de base de données
    Copy-Item -Path $dbFile -Destination $backupFile -Force
    $fileSize = (Get-Item $backupFile).Length / 1KB
    Write-Host "Backup créé avec succès : $backupFile ($([math]::Round($fileSize, 2)) KB)" -ForegroundColor Green
    
    # Garder seulement les 10 derniers backups
    Get-ChildItem -Path $backupDir -Filter "database_*.sqlite" | 
        Sort-Object LastWriteTime -Descending | 
        Select-Object -Skip 10 | 
        Remove-Item -Force
    
    Write-Host "Sauvegarde terminée !" -ForegroundColor Green
} else {
    Write-Host "ERREUR : Le fichier de base de données n'existe pas : $dbFile" -ForegroundColor Red
    exit 1
}
