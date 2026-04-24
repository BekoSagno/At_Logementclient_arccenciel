# 🚀 Guide Rapide de Préparation - AT Logement

## ⚡ Démarrage Rapide

### Option 1 : Script Automatique (Recommandé)

```powershell
cd at-logement
.\prepare-project.ps1
```

Ce script va automatiquement :
- ✅ Vérifier PHP, Composer, Node.js, NPM
- ✅ Vérifier les extensions PHP
- ✅ Installer/mettre à jour les dépendances
- ✅ Configurer la base de données
- ✅ Créer l'utilisateur admin
- ✅ Préparer le stockage

### Option 2 : Installation Manuelle

#### 1. Installer les dépendances PHP
```bash
composer install
```

#### 2. Installer les dépendances JavaScript
```bash
npm install
```

#### 3. Configurer l'environnement
```bash
# Créer .env si absent
copy .env.example .env

# Générer la clé
php artisan key:generate
```

#### 4. Préparer la base de données
```bash
# Créer la base SQLite
New-Item -ItemType File -Path database\database.sqlite -Force

# Exécuter les migrations
php artisan migrate

# Créer l'admin
php artisan db:seed --class=AdminUserSeeder
```

#### 5. Créer le lien storage
```bash
php artisan storage:link
```

#### 6. Lancer les serveurs
```bash
# Terminal 1 : Serveur Laravel
php artisan serve

# Terminal 2 : Vite (assets frontend)
npm run dev
```

---

## 🔍 Vérifications Importantes

### Extensions PHP Requises

Vérifiez que ces extensions sont activées :
```bash
php -m | findstr "pdo mbstring xml ctype json openssl tokenizer fileinfo"
```

**Extensions recommandées :**
- `gd` - Pour la compression d'images
- `intl` - Pour le formatage (évite les erreurs dans Filament)
- `zip` - Pour la compression

**Activer dans php.ini :**
1. Trouver le fichier : `php --ini`
2. Ouvrir `php.ini`
3. Décommenter les lignes (enlever le `;`) :
   ```ini
   extension=gd
   extension=intl
   extension=zip
   ```
4. Redémarrer PHP/serveur web

---

## 📦 Dépendances du Projet

### Backend (PHP)
- Laravel 11.x
- Filament 4.0
- PHP 8.2+

### Frontend (JavaScript)
- Tailwind CSS 3.x
- Alpine.js 3.x
- Vite 5.x

---

## 🌐 Accès au Projet

### Site Public
- URL : http://localhost:8000

### Panel Admin
- URL : http://localhost:8000/admin
- Email : `admin@at-logement.com`
- Mot de passe : `password`

⚠️ **Changez le mot de passe en production !**

---

## 🐛 Problèmes Courants

### Erreur : Extension PHP manquante
**Solution :** Activez l'extension dans `php.ini` et redémarrez

### Erreur : Port déjà utilisé
**Solution :** Utilisez un autre port
```bash
php artisan serve --port=8001
```

### Erreur : Permission denied
**Solution :** Exécutez PowerShell en tant qu'administrateur

### Erreur : Composer memory limit
**Solution :**
```bash
php -d memory_limit=-1 composer install
```

---

## 📚 Documentation Complète

Consultez `SETUP_GUIDE.md` pour le guide détaillé complet.

---

**Bon développement ! 🎉**
