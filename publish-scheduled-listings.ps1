# Script pour publier les annonces programmées
# Ce script doit être exécuté par le Planificateur de tâches Windows

# Définir le répertoire du projet
$projectPath = "C:\Users\PC\Desktop\at-logement\at-logement"

# Fichier de log
$logFile = "$projectPath\storage\logs\scheduled-publish.log"

# Fonction pour écrire dans le log
function Write-Log {
    param([string]$Message, [string]$Level = "INFO")
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $logMessage = "[$timestamp] [$Level] $Message"
    
    # Créer le dossier logs s'il n'existe pas
    $logDir = Split-Path -Parent $logFile
    if (-not (Test-Path $logDir)) {
        New-Item -ItemType Directory -Path $logDir -Force | Out-Null
    }
    
    Add-Content -Path $logFile -Value $logMessage
    
    # Afficher aussi dans la console
    Write-Host $logMessage
}

try {
    Write-Log "Démarrage de la publication des annonces programmées"
    
    # Vérifier que le répertoire existe
    if (-not (Test-Path $projectPath)) {
        Write-Log "Erreur : Le répertoire du projet n'existe pas : $projectPath" "ERROR"
        exit 1
    }
    
    # Changer vers le répertoire du projet
    Set-Location $projectPath
    Write-Log "Répertoire changé vers : $projectPath"
    
    # Vérifier que PHP est disponible
    $phpCheck = Get-Command php -ErrorAction SilentlyContinue
    if (-not $phpCheck) {
        Write-Log "Erreur : PHP n'est pas trouvé dans le PATH. Vérifiez votre installation PHP." "ERROR"
        exit 1
    }
    
    Write-Log "PHP trouvé : $($phpCheck.Source)"
    
    # Exécuter la commande artisan
    Write-Log "Exécution de la commande : php artisan listings:publish-scheduled"
    $output = php artisan listings:publish-scheduled 2>&1
    
    # Afficher la sortie
    if ($output) {
        Write-Log "Sortie de la commande : $output"
    }
    
    Write-Log "Publication terminée avec succès"
    exit 0
    
} catch {
    Write-Log "Erreur lors de l'exécution : $($_.Exception.Message)" "ERROR"
    Write-Log "Stack trace : $($_.ScriptStackTrace)" "ERROR"
    exit 1
}
