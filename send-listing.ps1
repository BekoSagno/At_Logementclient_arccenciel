# Script pour envoyer une annonce vers Make.com
$webhookUrl = "https://hook.eu1.make.com/kdzl78yqt6kdbzuwb25leedh2n51qdof"

# Lire le fichier JSON
$jsonPath = Join-Path $PSScriptRoot "listing-data.json"
$jsonContent = Get-Content -Path $jsonPath -Raw -Encoding UTF8

Write-Host "🔄 Relance de l'annonce vers Make.com..." -ForegroundColor Cyan
Write-Host "Webhook: $webhookUrl" -ForegroundColor Gray
Write-Host ""

try {
    $response = Invoke-RestMethod -Uri $webhookUrl -Method Post -Body $jsonContent -ContentType "application/json; charset=utf-8" -ErrorAction Stop
    
    Write-Host "✅ Annonce relancée avec succès vers Make.com !" -ForegroundColor Green
    Write-Host "Réponse:" -ForegroundColor Gray
    $response | ConvertTo-Json -Depth 10
} catch {
    Write-Host "❌ Erreur lors de l'envoi:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Réponse du serveur:" -ForegroundColor Yellow
        Write-Host $responseBody -ForegroundColor Yellow
    }
    exit 1
}
