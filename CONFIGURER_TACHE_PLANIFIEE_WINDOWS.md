# Configuration d'une tâche planifiée Windows pour la publication automatique

Ce guide explique comment configurer une tâche planifiée Windows pour exécuter automatiquement la commande de publication des annonces programmées.

---

## Méthode 1 : Via l'interface graphique du Planificateur de tâches

### Étape 1 : Ouvrir le Planificateur de tâches
1. Appuyez sur `Windows + R`
2. Tapez `taskschd.msc` et appuyez sur Entrée
3. Le Planificateur de tâches s'ouvre

### Étape 2 : Créer une nouvelle tâche
1. Dans le panneau de droite, cliquez sur **"Créer une tâche..."** (pas "Créer une tâche de base")
2. Dans l'onglet **"Général"** :
   - **Nom** : `Publier annonces programmées AT Logement`
   - **Description** : `Publie automatiquement les annonces dont la date de publication programmée est atteinte`
   - Cochez **"Exécuter que l'utilisateur soit connecté ou non"**
   - Cochez **"Exécuter avec les privilèges les plus élevés"**

### Étape 3 : Configurer le déclencheur
1. Allez dans l'onglet **"Déclencheurs"**
2. Cliquez sur **"Nouveau..."**
3. Configurez :
   - **Commencer la tâche** : `Selon une planification`
   - **Paramètres** : `Répéter la tâche toutes les` → `1` → `minutes`
   - **Durée** : `Indéfiniment` (ou définissez une date de fin si vous préférez)
4. Cliquez sur **"OK"**

### Étape 4 : Configurer l'action
1. Allez dans l'onglet **"Actions"**
2. Cliquez sur **"Nouveau..."**
3. Configurez :
   - **Action** : `Démarrer un programme`
   - **Programme/script** : `powershell.exe`
   - **Ajouter des arguments** : `-ExecutionPolicy Bypass -File "C:\Users\PC\Desktop\at-logement\at-logement\publish-scheduled-listings.ps1"`
   - **Démarrer dans** : `C:\Users\PC\Desktop\at-logement\at-logement`
4. Cliquez sur **"OK"**

### Étape 5 : Configurer les conditions (optionnel)
1. Allez dans l'onglet **"Conditions"**
2. Décochez **"Mettre en veille l'ordinateur uniquement sur secteur"** si vous voulez que la tâche s'exécute même sur batterie
3. Cochez **"Réveiller l'ordinateur pour exécuter cette tâche"** si nécessaire

### Étape 6 : Configurer les paramètres
1. Allez dans l'onglet **"Paramètres"**
2. Cochez **"Autoriser l'exécution de la tâche à la demande"**
3. Cochez **"Exécuter la tâche dès que possible après une exécution programmée manquée"**
4. Si la tâche ne se termine pas, choisissez **"Arrêter la tâche si elle s'exécute plus de"** → `5 minutes`

### Étape 7 : Enregistrer la tâche
1. Cliquez sur **"OK"**
2. Entrez le mot de passe de votre compte Windows si demandé
3. La tâche est maintenant créée et active

---

## Méthode 2 : Via PowerShell (ligne de commande)

Ouvrez PowerShell en tant qu'administrateur et exécutez :

```powershell
$action = New-ScheduledTaskAction -Execute "powershell.exe" -Argument "-ExecutionPolicy Bypass -File `"C:\Users\PC\Desktop\at-logement\at-logement\publish-scheduled-listings.ps1`"" -WorkingDirectory "C:\Users\PC\Desktop\at-logement\at-logement"

$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1) -RepetitionDuration (New-TimeSpan -Days 365)

$settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable

$principal = New-ScheduledTaskPrincipal -UserId "$env:USERDOMAIN\$env:USERNAME" -LogonType S4U -RunLevel Highest

Register-ScheduledTask -TaskName "Publier annonces programmées AT Logement" -Action $action -Trigger $trigger -Settings $settings -Principal $principal -Description "Publie automatiquement les annonces dont la date de publication programmée est atteinte"
```

---

## Vérification

### Tester la tâche manuellement
1. Dans le Planificateur de tâches, trouvez votre tâche
2. Clic droit → **"Exécuter"**
3. Vérifiez le fichier de log : `C:\Users\PC\Desktop\at-logement\at-logement\storage\logs\scheduled-publish.log`

### Vérifier l'historique
1. Dans le Planificateur de tâches, sélectionnez votre tâche
2. En bas, allez dans l'onglet **"Historique"**
3. Vous verrez toutes les exécutions de la tâche

---

## Dépannage

### La tâche ne s'exécute pas
1. Vérifiez que PHP est dans le PATH système
2. Vérifiez les permissions du script PowerShell
3. Vérifiez l'historique de la tâche pour voir les erreurs

### Erreur "ExecutionPolicy"
Si vous obtenez une erreur liée à l'ExecutionPolicy, exécutez dans PowerShell (en tant qu'administrateur) :
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Vérifier que PHP est accessible
Dans PowerShell, testez :
```powershell
php --version
```

Si cela ne fonctionne pas, vous devrez peut-être utiliser le chemin complet vers PHP, par exemple :
```
C:\xampp\php\php.exe
```

Dans ce cas, modifiez le script `publish-scheduled-listings.ps1` pour utiliser le chemin complet.

---

## Alternative : Exécution toutes les 5 minutes (recommandé)

Si l'exécution toutes les minutes est trop fréquente, vous pouvez configurer pour toutes les 5 minutes :
- Dans le déclencheur, changez `1` minute en `5` minutes
- Ou modifiez la commande PowerShell : `-RepetitionInterval (New-TimeSpan -Minutes 5)`
