# 🔧 ACTIVATION DU WEBHOOK DANS MAKE.COM

## 🎯 Problème

Quand vous cliquez sur le module Webhook, vous voyez l'interface de configuration mais Make.com ne connaît pas encore la structure des données.

## ✅ Solution : Définir la structure des données

### Option 1 : Envoyer des données de test (Recommandé)

1. **Dans Make.com** :
   - Cliquez sur **"Sauvegarder"** dans le module Webhook
   - Cliquez sur **"Exécuter une fois"** (en bas à gauche)
   - Le webhook est maintenant en attente de données

2. **Depuis votre site Laravel** :
   - Créez une annonce de test OU
   - Utilisez le script de test ci-dessous

3. **Retour dans Make.com** :
   - Les données seront automatiquement reçues
   - Make.com apprendra la structure des données
   - Vous pourrez ensuite mapper les champs dans les modules suivants

### Option 2 : Définir manuellement la structure

1. Dans le module Webhook, cliquez sur **"Redéfinir la structure des données"**
2. Cliquez sur **"Ajouter"** pour ajouter des champs
3. Définissez la structure suivante :

```
event (Text)
listing (Collection)
  ├─ id (Number)
  ├─ title (Text)
  ├─ slug (Text)
  ├─ description (Text)
  ├─ type (Text)
  ├─ type_label (Text)
  ├─ price (Number)
  ├─ currency (Text)
  ├─ price_formatted (Text)
  ├─ address (Text)
  ├─ city (Text)
  ├─ location (Text)
  ├─ characteristics (Collection)
  │   ├─ bedrooms (Text)
  │   ├─ bathrooms (Text)
  │   └─ surface (Text)
  ├─ images (Collection of Text)
  ├─ videos (Collection of Text)
  ├─ thumbnail (Text)
  ├─ url (Text)
  ├─ hashtags (Collection of Text)
  ├─ published_at (Text)
  └─ is_featured (Boolean)
timestamp (Text)
```

---

## 🚀 ÉTAPE PAR ÉTAPE - ACTIVATION COMPLÈTE

### Étape 1 : Activer le webhook

1. Dans Make.com, dans le module Webhook :
   - Cliquez sur **"Sauvegarder"**
   - Cliquez sur **"Exécuter une fois"** (en bas à gauche, bouton violet avec icône play)
   - Le webhook est maintenant **actif** et attend des données

### Étape 2 : Envoyer des données de test

**Option A : Depuis l'admin (Recommandé)**

1. Allez sur `http://localhost:8000/admin/listings`
2. Créez une nouvelle annonce OU modifiez l'annonce ID 9
3. Activez le toggle **"Publié"** et définissez la date de publication
4. Sauvegardez
5. Les données seront automatiquement envoyées à Make.com

**Option B : Via script de test**

Exécutez cette commande dans votre terminal :

```bash
cd "C:\Users\PC\Desktop\at-logement\at-logement"
php artisan tinker
```

Puis dans tinker :

```php
$listing = App\Models\Listing::find(9);
App\Services\MakeWebhookService::sendListingToMake($listing, 'publish');
```

### Étape 3 : Vérifier la réception dans Make.com

1. Retournez dans Make.com
2. Cliquez sur le module **Webhook**
3. Vous devriez voir les données reçues
4. Make.com aura automatiquement appris la structure des données

### Étape 4 : Mapper les champs dans LinkedIn

Maintenant que Make.com connaît la structure :

1. Cliquez sur le module **LinkedIn**
2. Dans le champ **"URL de l'image"** :
   - Cliquez sur l'icône **Map** (`</>`)
   - Sélectionnez : `{{1.thumbnail}}` ou `{{1.images[0]}}`
3. Dans le champ **"Titre"** :
   - Mappez : `{{1.title}}`
4. Cliquez sur **"Sauvegarder"**

---

## 🔍 VÉRIFICATION

### Vérifier que le webhook est actif

1. Dans Make.com, regardez le module Webhook
2. Vous devriez voir un indicateur que le webhook est **actif** (généralement une icône verte ou un statut "En attente")
3. L'URL du webhook est visible : `https://hook.eu1.make.com/kdzl78yqt6kdbzuwb25leedh2n51qdof`

### Vérifier que les données sont reçues

1. Après avoir envoyé des données de test
2. Cliquez sur le module Webhook dans Make.com
3. Vous devriez voir les données reçues avec la structure JSON
4. Si vous voyez les données, c'est que tout fonctionne !

---

## ⚠️ IMPORTANT

- Le webhook doit être **activé** (cliquez sur "Exécuter une fois")
- Le scénario doit être **activé** (toggle ON en haut à droite)
- Les données doivent être envoyées **après** l'activation du webhook

---

## 🐛 Dépannage

### Le webhook ne reçoit pas les données

1. Vérifiez que le webhook est activé (cliquez sur "Exécuter une fois")
2. Vérifiez que le scénario est activé (toggle ON)
3. Vérifiez l'URL du webhook dans votre `.env` :
   ```env
   MAKE_WEBHOOK_URL=https://hook.eu1.make.com/kdzl78yqt6kdbzuwb25leedh2n51qdof
   ```
4. Testez manuellement avec le script de test

### Les données ne s'affichent pas dans Make.com

1. Attendez quelques secondes après l'envoi
2. Actualisez la page Make.com
3. Cliquez à nouveau sur le module Webhook
4. Vérifiez les logs de votre site : `storage/logs/laravel.log`

---

**Document créé le** : 2026-01-12
