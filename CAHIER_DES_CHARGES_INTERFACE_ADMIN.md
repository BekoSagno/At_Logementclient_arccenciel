# CAHIER DES CHARGES - INTERFACE ADMINISTRATEUR
## Site Web AT Logement - Panel d'Administration Filament

---

## 1. VUE D'ENSEMBLE

### 1.1 Objectif
L'interface administrateur est un panel de gestion complet permettant aux administrateurs de gérer les annonces immobilières, les messages des visiteurs, et toutes les données du site via Filament (framework d'administration Laravel).

### 1.2 Technologies Utilisées
- **Framework Backend** : Laravel (PHP)
- **Framework Admin** : Filament 3.x
- **Base de données** : MySQL/PostgreSQL
- **Authentification** : Laravel Breeze/Jetstream (système d'authentification Laravel)
- **Stockage** : Laravel Storage (disque `public`)

### 1.3 Accès
- **URL** : `/admin`
- **Authentification** : Requise (connexion obligatoire)
- **Middleware** : Protection CSRF, session, authentification

---

## 2. CONFIGURATION DU PANEL

### 2.1 AdminPanelProvider
**Fichier** : `app/Providers/Filament/AdminPanelProvider.php`

#### Configuration :
- **ID du panel** : `admin`
- **Chemin** : `/admin`
- **Couleur principale** : Amber (orange/ambre)
- **Page de connexion** : Activée
- **Découverte automatique** :
  - Resources : `app/Filament/Resources`
  - Pages : `app/Filament/Pages`
  - Widgets : `app/Filament/Widgets`

#### Middleware :
1. `EncryptCookies` : Chiffrement des cookies
2. `AddQueuedCookiesToResponse` : Gestion des cookies en file d'attente
3. `StartSession` : Démarrage de session
4. `AuthenticateSession` : Authentification de session
5. `ShareErrorsFromSession` : Partage des erreurs de session
6. `VerifyCsrfToken` : Vérification du token CSRF
7. `SubstituteBindings` : Substitution des bindings de route
8. `DisableBladeIconComponents` : Désactivation des composants d'icônes Blade
9. `DispatchServingFilamentEvent` : Dispatch des événements Filament
10. `SetFilamentLocale` : Configuration de la locale

#### Widgets par défaut :
- `AccountWidget` : Widget de compte utilisateur
- `FilamentInfoWidget` : Widget d'information Filament

---

## 3. RESSOURCES ADMINISTRATIVES

### 3.1 ListingResource (Gestion des Annonces)
**Fichier** : `app/Filament/Resources/ListingResource.php`
**Label de navigation** : "Annonces"
**Ordre de navigation** : 1 (par défaut)

#### 3.1.1 Formulaire de Création/Édition

##### Section 1 : Informations générales

**Champs** :

1. **Titre** (`title`)
   - Type : `Select` (dropdown)
   - Requis : Oui
   - Options prédéfinies (13 services) :
     - Locations de biens immobiliers
     - Ventes de biens immobiliers
     - Promotion immobilière
     - Etat des lieux
     - Gestion de biens immobiliers
     - Elaboration de contrat de location
     - Conseil Immobilier
     - Rénovation et achèvement
     - Service de nettoyage
     - Service de transport
     - Frigoriste-SOS-24/7
     - Plomberie-SOS-24/7
     - Electricité-SOS-24/7
   - Comportement :
     - `live()` : Mise à jour en temps réel
     - Génération automatique du slug lors de la sélection
     - Non recherchable (liste fixe)

2. **Slug** (`slug`)
   - Type : `TextInput`
   - Requis : Oui
   - Longueur max : 255 caractères
   - Unique : Oui (ignorant l'enregistrement actuel)
   - Génération automatique : À partir du titre sélectionné

3. **Description** (`description`)
   - Type : `Textarea`
   - Requis : Oui
   - Lignes : 3
   - Largeur : Pleine largeur (`columnSpanFull`)

4. **Statut du service** (`service_status`)
   - Type : `Select`
   - Requis : Non
   - Options :
     - `recherche` => "Recherche"
     - `propose` => "Propose"
     - `realise` => "Réalisé"
   - Visibilité conditionnelle : Visible uniquement si le titre sélectionné nécessite un statut (via `ListingServiceConfig::requiresServiceStatus()`)
   - Services nécessitant un statut :
     - Service de nettoyage
     - Service de transport
     - Frigoriste-SOS-24/7
     - Plomberie-SOS-24/7
     - Electricité-SOS-24/7
     - Rénovation et achèvement
     - Etat des lieux
     - Gestion de biens immobiliers

5. **Prix** (`price`)
   - Type : `TextInput` (numérique)
   - Requis : Non (optionnel)
   - Préfixe : "GNF"
   - Valeur min : 0
   - Valeur max : 999 999 999 999
   - Attributs :
     - Alignement texte : Gauche
     - Largeur min : 300px
     - Pattern : `[0-9]{1,12}`
     - Maxlength : 12
     - Inputmode : numeric

6. **Devise** (`currency`)
   - Type : `Hidden` (champ caché)
   - Valeur par défaut : "GNF"
   - Fixée automatiquement lors de la sauvegarde

##### Section 2 : Localisation

**Champs** :

1. **Adresse** (`address`)
   - Type : `TextInput`
   - Requis : Non
   - Longueur max : 255 caractères

2. **Ville** (`city`)
   - Type : `TextInput`
   - Requis : Non
   - Longueur max : 255 caractères

##### Section 3 : Champs personnalisés (Optionnel - 3 maximum)

**Description** : "Ajoutez jusqu'à 3 champs personnalisés avec un titre et une valeur"

**Champ** : `custom_fields`
- Type : `Repeater`
- Nombre min : 0
- Nombre max : 3
- Sous-champs :
  1. **Titre du champ** (`label`)
     - Type : `TextInput`
     - Requis : Oui
     - Longueur max : 255 caractères
  2. **Valeur** (`value`)
     - Type : `TextInput`
     - Requis : Oui
     - Longueur max : 255 caractères
- Fonctionnalités :
  - Collapsible (repliables)
  - Label personnalisé : Affiche le label du champ
  - Colonnes : 2
- Section : Repliée par défaut (`collapsed()`)

##### Section 4 : Médias (Images et Vidéos)

**Description** : "Téléchargez des images ou des vidéos. Les images peuvent être rognées."

**Champ** : `images`
- Type : `FileUpload`
- Multiple : Oui
- Réordonnable : Oui (drag & drop)
- Répertoire : `listings`
- Disque : `public`
- Taille max : 100 MB par fichier (102400 KB)
- Types acceptés :
  - Images : `image/jpeg`, `image/png`, `image/webp`
  - Vidéos : `video/mp4`, `video/quicktime`, `video/x-msvideo`, `video/webm`
- Éditeur d'image : Activé (`imageEditor()`)
- Ratios d'aspect disponibles :
  - Libre (null)
  - 16:9
  - 4:3
  - 1:1 (carré)
- Ratio par défaut pour le rognage : 16:9

**Traitement automatique** :
- **Images** : Compression automatique après upload
  - Qualité : 85%
  - Taille max : 1920px (largeur ou hauteur)
  - Conversion PNG → JPEG (si applicable)
  - Conversion en WebP (si supporté)
- **Vidéos** : Compression via FFmpeg (si disponible)
  - Compression uniquement si > 50MB
  - Codec : H.264 (libx264)
  - Qualité : CRF 23 (élevée)
  - Audio : AAC 128k
  - Preset : medium

##### Section 5 : Liens Réseaux Sociaux (Optionnel)

**Description** : "Ajoutez les liens vers les publications de cette annonce sur vos réseaux sociaux"

**Champs** :
1. **Facebook URL** (`social_links_facebook`)
   - Type : `TextInput` (URL)
   - Placeholder : `https://www.facebook.com/...`
   - Longueur max : 255 caractères

2. **LinkedIn URL** (`social_links_linkedin`)
   - Type : `TextInput` (URL)
   - Placeholder : `https://www.linkedin.com/...`
   - Longueur max : 255 caractères

3. **X (Twitter) URL** (`social_links_twitter`)
   - Type : `TextInput` (URL)
   - Placeholder : `https://x.com/...`
   - Longueur max : 255 caractères

4. **Instagram URL** (`social_links_instagram`)
   - Type : `TextInput` (URL)
   - Placeholder : `https://www.instagram.com/...`
   - Longueur max : 255 caractères

5. **TikTok URL** (`social_links_tiktok`)
   - Type : `TextInput` (URL)
   - Placeholder : `https://www.tiktok.com/...`
   - Longueur max : 255 caractères

- Section : Repliée par défaut (`collapsed()`)
- Colonnes : 2

**Traitement** : Les champs individuels sont transformés en JSON (`social_links`) lors de la sauvegarde

##### Section 6 : Publication

**Champs** :

1. **Publié** (`status`)
   - Type : `Toggle` (interrupteur)
   - Valeur par défaut : `false`
   - Détermine si l'annonce est publiée

2. **Mise en avant** (`is_featured`)
   - Type : `Toggle` (interrupteur)
   - Valeur par défaut : `false`
   - Détermine si l'annonce apparaît en premier dans les résultats

3. **Date de publication** (`published_at`)
   - Type : `DateTimePicker`
   - Valeur par défaut : Date/heure actuelle
   - Format d'affichage : Date et heure

#### 3.1.2 Logique de Traitement des Données

##### Création (CreateListing.php)

**Méthode** : `mutateFormDataBeforeCreate()`

**Traitements** :
1. **Devise** : Fixée à "GNF"
2. **Prix** : Conversion des valeurs vides en `null`
3. **Liens sociaux** : Transformation des champs `social_links_*` en tableau JSON
4. **Champs personnalisés** : Nettoyage des entrées vides, conversion en `null` si vide
5. **Service status** : Préservation (peut être `null`)

**Méthode** : `afterCreate()`

**Traitements** :
1. **Compression des médias** : Compression automatique des images et vidéos après création
2. **Mise à jour** : Mise à jour de l'enregistrement avec les chemins compressés

##### Édition (EditListing.php)

**Méthode** : `mutateFormDataBeforeSave()`

**Traitements** :
1. **Devise** : Fixée à "GNF"
2. **Prix** : Conversion des valeurs vides en `null`
3. **Mise à jour** : Si `is_featured` change, mise à jour de `updated_at` pour le tri
4. **Liens sociaux** : Transformation des champs `social_links_*` en tableau JSON
5. **Champs personnalisés** : Nettoyage des entrées vides
6. **Service status** : Préservation

**Méthode** : `fillForm()`

**Traitements** :
1. **Décomposition des liens sociaux** : Transformation du JSON en champs individuels pour l'affichage
2. **Champs personnalisés** : Conversion en tableau si nécessaire

**Méthode** : `afterSave()`

**Traitements** :
1. **Détection des nouveaux médias** : Comparaison avec les médias existants
2. **Compression sélective** : Compression uniquement des nouveaux médias
3. **Mise à jour** : Mise à jour avec les chemins compressés

#### 3.1.3 Tableau de Liste

**Fichier** : `app/Filament/Resources/ListingResource.php` (méthode `table()`)

##### Tri par défaut :
1. Mises en avant (`is_featured`) : Décroissant
2. Date de mise à jour (`updated_at`) : Décroissant
3. Date de publication (`published_at`) : Décroissant
4. Date de création (`created_at`) : Décroissant

##### Pagination :
- Résultats par page : 25 (par défaut)

##### Colonnes :

1. **Image**
   - Type : `ImageColumn`
   - Taille : 50px (circulaire)
   - Source : Première image du tableau `images`
   - Image par défaut : `/images/placeholder.png`
   - Lazy loading : Activé

2. **Titre**
   - Type : `TextColumn`
   - Recherchable : Oui
   - Triable : Oui
   - Style : Gras

3. **Type**
   - Type : `TextColumn` (Badge)
   - Triable : Oui
   - Couleurs :
     - `residential` : Vert (success)
     - `commercial` : Jaune (warning)
     - `land` : Bleu (info)
     - `service` : Gris (gray)
   - Formatage :
     - `residential` => "Résidentiel"
     - `commercial` => "Commercial"
     - `land` => "Terrain"
     - `service` => "Service"

4. **Prix**
   - Type : `TextColumn`
   - Triable : Oui
   - Formatage : `X XXX XXX GNF` (format français)

5. **Statut**
   - Type : `IconColumn` (Boolean)
   - Toggleable : Oui (clic pour changer)
   - Affiche : Icône de statut (publié/non publié)

6. **Date de publication**
   - Type : `TextColumn`
   - Triable : Oui
   - Format : `d/m/Y H:i` (ex: 01/01/2025 14:30)

##### Filtres :

1. **Type**
   - Type : `SelectFilter`
   - Options :
     - Résidentiel
     - Commercial
     - Terrain
     - Service

2. **Statut**
   - Type : `TernaryFilter`
   - Options :
     - Tous
     - Publiés
     - Brouillons

##### Actions :

1. **Édition** : Bouton d'édition pour chaque ligne
2. **Suppression** : Bouton de suppression pour chaque ligne

##### Actions groupées (Bulk Actions) :

1. **Suppression groupée** : Suppression de plusieurs annonces sélectionnées

---

### 3.2 MessageResource (Gestion des Messages)
**Fichier** : `app/Filament/Resources/MessageResource.php`
**Label de navigation** : "Messages"
**Ordre de navigation** : 2

#### 3.2.1 Formulaire de Visualisation

**Note** : Les messages sont en lecture seule (tous les champs sont `disabled()`)

##### Section : Informations du message

**Champs** :

1. **Annonce concernée** (`listing_id`)
   - Type : `Select` (relation)
   - Relation : `listing` (titre)
   - Recherchable : Oui
   - Préchargement : Oui
   - Nullable : Oui

2. **Nom complet** (`name`)
   - Type : `TextInput`
   - Requis : Oui
   - Longueur max : 255 caractères
   - Désactivé : Oui

3. **Email** (`email`)
   - Type : `TextInput` (email)
   - Requis : Oui
   - Longueur max : 255 caractères
   - Désactivé : Oui

4. **Téléphone** (`phone`)
   - Type : `TextInput` (tel)
   - Requis : Oui
   - Longueur max : 255 caractères
   - Désactivé : Oui

5. **Message** (`message`)
   - Type : `Textarea`
   - Lignes : 6
   - Largeur : Pleine largeur
   - Désactivé : Oui

6. **Lu le** (`read_at`)
   - Type : `DateTimePicker`
   - Format d'affichage : `d/m/Y H:i`
   - Désactivé : Oui

#### 3.2.2 Tableau de Liste

##### Tri par défaut :
- Date de création (`created_at`) : Décroissant (plus récents en premier)

##### Pagination :
- Résultats par page : 25 (par défaut)

##### Colonnes :

1. **Annonce**
   - Type : `TextColumn`
   - Source : `listing.title`
   - Recherchable : Oui
   - Triable : Oui
   - Limite : 30 caractères
   - Valeur par défaut : "N/A" (si pas d'annonce)
   - Lien : Vers la page d'édition de l'annonce (nouvel onglet)

2. **Nom**
   - Type : `TextColumn`
   - Recherchable : Oui
   - Triable : Oui

3. **Email**
   - Type : `TextColumn`
   - Recherchable : Oui
   - Triable : Oui
   - Copiable : Oui (bouton de copie)

4. **Téléphone**
   - Type : `TextColumn`
   - Recherchable : Oui
   - Copiable : Oui (bouton de copie)

5. **Lu**
   - Type : `IconColumn` (Boolean)
   - Triable : Oui
   - Icônes :
     - Lu : `heroicon-o-check-circle` (vert - success)
     - Non lu : `heroicon-o-x-circle` (rouge - danger)

6. **Reçu le**
   - Type : `TextColumn`
   - Source : `created_at`
   - Format : `d/m/Y H:i`
   - Triable : Oui

##### Filtres :

1. **Statut de lecture**
   - Type : `TernaryFilter`
   - Options :
     - Tous les messages
     - Messages lus
     - Messages non lus

##### Actions :

1. **Visualisation** : Bouton de visualisation (page dédiée)
2. **Suppression** : Bouton de suppression

##### Actions groupées (Bulk Actions) :

1. **Suppression groupée** : Suppression de plusieurs messages sélectionnés

#### 3.2.3 Page de Visualisation

**Fichier** : `app/Filament/Resources/MessageResource/Pages/ViewMessage.php`

##### Actions dans le header :

1. **Édition** : Bouton d'édition (standard Filament)
2. **Suppression** : Bouton de suppression (standard Filament)
3. **Marquer comme lu** :
   - Label : "Marquer comme lu"
   - Icône : `heroicon-o-check-circle`
   - Couleur : Success (vert)
   - Visibilité : Uniquement si `read_at === null`
   - Action : Met à jour `read_at` à la date/heure actuelle
   - Confirmation : Requise avant l'action

---

## 4. CONFIGURATION DES SERVICES

### 4.1 ListingServiceConfig
**Fichier** : `app/Filament/Resources/ListingServiceConfig.php`

#### 4.1.1 Méthode `requiresServiceStatus()`

**Objectif** : Détermine si un service nécessite un champ de statut

**Services nécessitant un statut** :
- Service de nettoyage
- Service de transport
- Frigoriste-SOS-24/7
- Plomberie-SOS-24/7
- Electricité-SOS-24/7
- Rénovation et achèvement
- Etat des lieux
- Gestion de biens immobiliers

**Retour** : `bool` (true si le service nécessite un statut)

#### 4.1.2 Méthode `requiresPrice()`

**Objectif** : Détermine si un service nécessite un prix

**Retour** : Toujours `true` (tous les services peuvent avoir un prix, mais c'est optionnel)

#### 4.1.3 Méthode `getSuggestedCustomFields()`

**Objectif** : Retourne des suggestions de champs personnalisés pour chaque service

**Retour** : Tableau associatif avec jusqu'à 3 suggestions par service (label + exemple)

**Exemples de suggestions** :
- **Locations** : Durée de location, Caution, Disponibilité
- **Ventes** : Type de bien, État, Disponibilité
- **Promotion** : Phase du projet, Nombre d'unités, Livraison prévue
- **Services SOS** : Type d'intervention, Type d'appareil/Zone/Puissance, Urgence

---

## 5. COMPRESSION DES MÉDIAS

### 5.1 Compression d'Images

**Méthode** : `compressImageFile()` (dans CreateListing.php et EditListing.php)

#### Paramètres :
- **Qualité** : 85/100 (élevée pour meilleure qualité visuelle)
- **Taille max** : 1920px (largeur ou hauteur, ratio préservé)
- **Formats supportés** : JPEG, PNG, WebP

#### Traitements :
1. **Redimensionnement** : Si l'image dépasse 1920px, redimensionnement proportionnel
2. **Conversion PNG → JPEG** : Les PNG sont convertis en JPEG pour réduire la taille
3. **Optimisation WebP** : Si supporté, conversion en WebP avec qualité adaptée
4. **Préservation de la transparence** : Pour les PNG (avant conversion)

#### Bibliothèque utilisée : GD (PHP)

### 5.2 Compression de Vidéos

**Méthode** : `compressVideoFile()` (dans CreateListing.php et EditListing.php)

#### Conditions :
- **Compression uniquement si** : Taille > 50MB
- **Outil** : FFmpeg (si disponible)

#### Paramètres FFmpeg :
- **Codec vidéo** : H.264 (libx264)
- **Qualité** : CRF 23 (élevée, 18-28 : plus bas = meilleure qualité)
- **Preset** : medium (équilibre vitesse/compression)
- **Codec audio** : AAC
- **Bitrate audio** : 128k
- **Format** : MP4 avec faststart (lecture progressive)

#### Chemins FFmpeg testés :
- `ffmpeg` (dans le PATH)
- `/usr/bin/ffmpeg`
- `/usr/local/bin/ffmpeg`
- `C:\ffmpeg\bin\ffmpeg.exe`
- `C:\Program Files\ffmpeg\bin\ffmpeg.exe`

#### Logique :
1. Vérification de la disponibilité de FFmpeg
2. Vérification de la taille du fichier
3. Compression dans un fichier temporaire
4. Remplacement de l'original uniquement si la version compressée est plus petite
5. Mise à jour du chemin dans la base de données

---

## 6. STRUCTURE DE BASE DE DONNÉES

### 6.1 Table `listings`

#### Colonnes :

**Identifiant** :
- `id` : BigInt, Primary Key, Auto-increment

**Informations générales** :
- `title` : String (255), Requis
- `slug` : String (255), Unique, Requis
- `description` : Text, Requis

**Prix et devise** :
- `price` : Decimal (15,2), Nullable
- `currency` : String, Default "GNF"

**Classification** :
- `type` : Enum ('residential', 'commercial', 'land', 'service')
- `service_status` : String, Nullable (recherche/propose/realise)

**Publication** :
- `status` : Boolean, Default false
- `published_at` : Timestamp, Nullable
- `is_featured` : Boolean, Default false

**Médias** :
- `images` : JSON, Nullable (tableau de chemins)

**Localisation** :
- `address` : String (255), Nullable
- `city` : String (255), Nullable

**Caractéristiques (Résidentiel)** :
- `bedrooms` : Integer, Nullable
- `bathrooms` : Integer, Nullable
- `surface` : Integer, Nullable (m²)

**Caractéristiques (Terrain)** :
- `document_type` : String (255), Nullable
- `surface` : Integer, Nullable (m²)

**Données supplémentaires** :
- `amenities` : JSON, Nullable (tableau de tags)
- `social_links` : JSON, Nullable (objet avec clés : facebook, linkedin, twitter, instagram, tiktok)
- `custom_fields` : JSON, Nullable (tableau d'objets avec `label` et `value`, max 3)

**Timestamps** :
- `created_at` : Timestamp
- `updated_at` : Timestamp

### 6.2 Table `messages`

#### Colonnes :

**Identifiant** :
- `id` : BigInt, Primary Key, Auto-increment

**Relation** :
- `listing_id` : Foreign Key vers `listings.id`, Nullable, On Delete Set Null

**Informations du contact** :
- `name` : String (255), Requis
- `email` : String (255), Requis
- `phone` : String (255), Requis
- `message` : Text, Nullable

**Statut** :
- `read_at` : Timestamp, Nullable

**Timestamps** :
- `created_at` : Timestamp
- `updated_at` : Timestamp

---

## 7. FONCTIONNALITÉS AVANCÉES

### 7.1 Champs Dynamiques

#### Visibilité conditionnelle :
- Les champs s'affichent/masquent selon la valeur d'autres champs
- Exemple : `service_status` visible uniquement pour certains services

#### Génération automatique :
- Le slug est généré automatiquement à partir du titre sélectionné

### 7.2 Validation des Données

#### Niveau formulaire :
- Validation HTML5 (attributs `required`, `maxlength`, `pattern`, etc.)
- Validation Filament (méthodes `->required()`, `->maxLength()`, etc.)

#### Niveau serveur :
- Validation Laravel dans les méthodes `mutateFormDataBeforeCreate()` et `mutateFormDataBeforeSave()`
- Nettoyage des données (conversion null, transformation JSON, etc.)

### 7.3 Gestion des Erreurs

#### Logging :
- Erreurs de compression : Loggées avec `\Log::warning()`
- Informations : Loggées avec `\Log::info()`

#### Gestion gracieuse :
- En cas d'erreur de compression, le fichier original est conservé
- Les erreurs n'empêchent pas la sauvegarde de l'annonce

---

## 8. SÉCURITÉ

### 8.1 Authentification
- Connexion requise pour accéder au panel
- Middleware d'authentification Filament

### 8.2 Protection CSRF
- Token CSRF vérifié sur toutes les requêtes
- Middleware `VerifyCsrfToken`

### 8.3 Validation des Fichiers
- Types MIME vérifiés
- Taille maximale limitée (100MB)
- Stockage sécurisé dans `storage/app/public/listings/`

### 8.4 Protection SQL Injection
- Utilisation d'Eloquent ORM (requêtes préparées)
- Validation et échappement des entrées utilisateur

---

## 9. PERFORMANCES

### 9.1 Optimisation des Images
- Compression automatique pour réduire la taille
- Conversion en formats optimisés (JPEG, WebP)
- Redimensionnement pour limiter la taille

### 9.2 Optimisation des Vidéos
- Compression uniquement si nécessaire (> 50MB)
- Codec H.264 pour compatibilité maximale
- Faststart pour lecture progressive

### 9.3 Requêtes Base de Données
- Relations préchargées (`with('listing')`)
- Index sur les colonnes fréquemment utilisées (slug, type, status)
- Pagination pour limiter les résultats

---

## 10. INTERFACE UTILISATEUR

### 10.1 Design
- Framework : Filament 3.x (design moderne et responsive)
- Couleur principale : Amber (orange/ambre)
- Navigation : Sidebar avec icônes et labels

### 10.2 Responsive
- Interface adaptative (mobile, tablette, desktop)
- Tableaux avec scroll horizontal si nécessaire
- Formulaires adaptés aux petits écrans

### 10.3 Accessibilité
- Navigation clavier supportée
- Labels et descriptions clairs
- Contraste respecté (WCAG)

---

## 11. WORKFLOW DE CRÉATION D'ANNONCE

### 11.1 Étapes

1. **Sélection du titre** : Choisir parmi les 13 services prédéfinis
2. **Génération du slug** : Automatique à partir du titre
3. **Remplissage des informations** :
   - Description
   - Statut du service (si applicable)
   - Prix (optionnel)
   - Localisation
   - Champs personnalisés (optionnel, max 3)
4. **Upload des médias** : Images et/ou vidéos (avec édition possible)
5. **Liens réseaux sociaux** : Optionnel
6. **Publication** : Activer le statut et définir la date
7. **Mise en avant** : Optionnel (pour apparaître en premier)

### 11.2 Traitements Automatiques

1. **Compression des médias** : Après sauvegarde
2. **Transformation des données** : Liens sociaux en JSON, nettoyage des champs vides
3. **Mise à jour des timestamps** : `updated_at` si mise en avant

---

## 12. WORKFLOW DE GESTION DES MESSAGES

### 12.1 Réception
- Messages reçus via le formulaire public
- Stockage automatique dans la base de données
- Statut initial : Non lu (`read_at = null`)

### 12.2 Consultation
1. Liste des messages dans le tableau
2. Filtrage par statut (lu/non lu)
3. Visualisation détaillée
4. Marquer comme lu (action manuelle)

### 12.3 Actions
- Visualisation complète
- Suppression individuelle ou groupée
- Lien vers l'annonce concernée (si applicable)

---

## 13. MAINTENANCE ET ÉVOLUTION

### 13.1 Fichiers Clés

**Resources** :
- `app/Filament/Resources/ListingResource.php`
- `app/Filament/Resources/MessageResource.php`
- `app/Filament/Resources/ListingServiceConfig.php`

**Pages** :
- `app/Filament/Resources/ListingResource/Pages/CreateListing.php`
- `app/Filament/Resources/ListingResource/Pages/EditListing.php`
- `app/Filament/Resources/MessageResource/Pages/ViewMessage.php`

**Configuration** :
- `app/Providers/Filament/AdminPanelProvider.php`

**Modèles** :
- `app/Models/Listing.php`
- `app/Models/Message.php`

### 13.2 Migrations
- `database/migrations/2025_12_26_180924_create_listings_table.php`
- `database/migrations/2025_12_29_070306_create_messages_table.php`
- `database/migrations/2026_01_01_061825_add_service_fields_to_listings_table.php`
- `database/migrations/2026_01_01_065101_make_price_nullable_in_listings_table.php`

---

## 14. FONCTIONNALITÉS FUTURES (À IMPLÉMENTER)

### 14.1 Améliorations Suggérées
- [ ] Export des annonces (CSV, Excel)
- [ ] Import en masse d'annonces
- [ ] Statistiques et rapports (dashboard)
- [ ] Gestion des utilisateurs admin
- [ ] Historique des modifications
- [ ] Prévisualisation avant publication
- [ ] Planification de publication (publication différée)
- [ ] Duplication d'annonces
- [ ] Templates d'annonces
- [ ] Gestion des catégories/tags avancée
- [ ] Intégration avec API externes (Google Maps, etc.)
- [ ] Notifications par email pour nouveaux messages
- [ ] Réponses aux messages depuis l'admin
- [ ] Recherche avancée dans les messages
- [ ] Export des messages

---

## 15. NOTES TECHNIQUES

### 15.1 Filament
- Version : 3.x
- Documentation : https://filamentphp.com/docs
- Composants utilisés : Forms, Tables, Actions, Filters

### 15.2 Laravel
- Version : 10.x / 11.x
- Storage : Disque `public` pour les fichiers uploadés
- Relations : Eloquent ORM

### 15.3 Dépendances
- **GD** : Pour la compression d'images (extension PHP)
- **FFmpeg** : Pour la compression de vidéos (optionnel, externe)

---

## 16. DÉPANNAGE

### 16.1 Problèmes Courants

**Compression d'images ne fonctionne pas** :
- Vérifier que l'extension GD est installée : `php -m | grep gd`
- Vérifier les permissions d'écriture dans `storage/app/public/listings/`

**Compression de vidéos ne fonctionne pas** :
- Vérifier que FFmpeg est installé : `ffmpeg -version`
- Vérifier les permissions d'exécution
- Les vidéos < 50MB ne sont pas compressées (comportement normal)

**Erreur lors de la sauvegarde** :
- Vérifier les logs : `storage/logs/laravel.log`
- Vérifier les contraintes de base de données (slug unique, etc.)

**Images non affichées** :
- Vérifier le lien symbolique : `php artisan storage:link`
- Vérifier les permissions du dossier `storage/app/public/`

---

**Document créé le** : 2026-01-01  
**Version** : 1.0  
**Auteur** : Équipe de développement AT Logement

