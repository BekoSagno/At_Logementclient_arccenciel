# CAHIER DES CHARGES - INTERFACE PUBLIQUE
## Site Web AT Logement - Plateforme Immobilière

---

## 1. VUE D'ENSEMBLE

### 1.1 Objectif
L'interface publique du site AT Logement est une plateforme web permettant aux utilisateurs de rechercher, consulter et contacter l'agence pour des biens immobiliers et services connexes en Guinée.

### 1.2 Technologies Utilisées
- **Framework Backend** : Laravel (PHP)
- **Framework Frontend** : Blade Templates (Laravel)
- **CSS Framework** : Tailwind CSS
- **JavaScript** : Alpine.js (pour l'interactivité)
- **Build Tool** : Vite
- **Base de données** : MySQL/PostgreSQL

### 1.3 Responsive Design
L'interface est entièrement responsive et s'adapte aux écrans :
- Mobile (< 640px)
- Tablette (640px - 1024px)
- Desktop (> 1024px)

---

## 2. STRUCTURE GÉNÉRALE

### 2.1 Header (En-tête)
**Composant** : `resources/views/components/header.blade.php`

#### Fonctionnalités :
- **Logo** : Logo AT Logement (2cm x 2cm sur desktop, adaptatif sur mobile)
- **Navigation Desktop** :
  - Liens dans un conteneur avec bordure orange arrondie :
    - Accueil
    - Annonce
    - Services
    - A Propos
  - Bouton "Mon Espace" (orange, arrondi)
- **Navigation Mobile** :
  - Menu hamburger
  - Menu déroulant avec les mêmes liens
- **Comportement** :
  - Header fixe en haut de la page
  - Masqué automatiquement lors du scroll vers le bas
  - Réapparaît lors du scroll vers le haut
  - Transitions fluides

### 2.2 Footer (Pied de page)
**Localisation** : `resources/views/welcome.blade.php` (ligne ~1242)

#### Sections :
1. **Informations de l'entreprise** :
   - Logo et nom "AT Logement"
   - Description
   - Numéro de téléphone : +224 612 345 678
2. **Liens rapides** :
   - Accueil
   - Vente
   - Location
   - Contact
3. **Services** :
   - Transaction
   - Gestion
   - Conseil
4. **Réseaux sociaux** :
   - Facebook
   - LinkedIn
   - Instagram
   - WhatsApp
   - Twitter
   - TikTok

**Style** : Fond dégradé noir/gris foncé, texte centré, éléments centrés

---

## 3. SECTIONS PRINCIPALES

### 3.1 Section Hero / Bannière
**ID** : `#accueil`
**Localisation** : `resources/views/welcome.blade.php` (ligne ~193)

#### Caractéristiques :
- **Image de fond** : `images/banniere.jpg` avec animation de zoom (20s)
- **Overlay** : Fond noir semi-transparent (50%)
- **Contenu** :
  - Titre principal : "Trouvez votre futur chez-vous"
  - Sous-titre : "L'immobilier en toute confiance avec AT Logement."
  - **Barre de recherche intégrée** :
    - Champ de recherche textuelle
    - Bouton "Rechercher" (orange)
    - Formulaire redirige vers `/listings/search`
- **Animations** :
  - Fade-in-up pour le titre
  - Fade-in-up avec délai pour le sous-titre
  - Fade-in-scale pour la barre de recherche

### 3.2 Section Filtres (Optionnelle)
**Localisation** : `resources/views/welcome.blade.php` (ligne ~248)

#### Fonctionnalités :
- Affichage conditionnel via Alpine.js (`x-show="showFilters"`)
- **Filtres disponibles** :
  1. **Localisation** : Dropdown (Conakry, Kindia, Kankan, Nzérékoré, Labé)
  2. **Type de bien** : Dropdown (Maison, Appartement, Villa, Terrain, Bureau, Local commercial)
  3. **Transaction** : Dropdown (Vente, Location)
  4. **Nombre de chambres** : Dropdown (1+, 2+, 3+, 4+, 5+)
  5. **Budget Min** : Input numérique (GNF)
  6. **Budget Max** : Input numérique (GNF)
- **Boutons** :
  - "Fermer" (gris)
  - "Appliquer les filtres" (orange)

### 3.3 Section Annonces (Dernières Opportunités)
**ID** : `#annonces`
**Localisation** : `resources/views/welcome.blade.php` (ligne ~362)

#### Caractéristiques :
- **Titre** : "Dernières Opportunités" avec double soulignement décoratif
- **Affichage** :
  - Grille responsive : 1 colonne (mobile), 2 colonnes (tablette), 3 colonnes (desktop)
  - Maximum 9 annonces affichées sur la page d'accueil
  - Tri : Annonces mises en avant en premier (par `updated_at`), puis par date de publication
- **Composant de carte** : `x-listing-card` (`resources/views/components/listing-card.blade.php`)

#### Carte d'annonce (`listing-card.blade.php`) :

**Structure** :
1. **Image** :
   - Hauteur fixe : 48 (192px)
   - Image de couverture (thumbnail) ou placeholder SVG
   - Zoom au survol (scale 1.1)
   - Badge de type/statut en haut à gauche
   - Badge de prix en haut à droite (si applicable)

2. **Badge Type/Statut** :
   - Si `service_status` existe :
     - "Recherche" (bleu - `bg-blue-500`)
     - "Propose" (vert - `bg-green-500`)
     - "Réalisé" (violet - `bg-purple-500`)
   - Sinon, affiche le type :
     - "Résidentiel" (bleu)
     - "Commercial" (violet)
     - "Terrain" (ambre)
     - "Service" (orange)

3. **Badge Prix** :
   - Affiché uniquement si le type n'est pas "service" ET si le prix existe
   - Format : `X XXX XXX GNF`
   - Style : Dégradé orange, arrondi

4. **Contenu** :
   - **Titre** : Gras, limite 2 lignes
   - **Description** : Limite 100 caractères, 2 lignes max
   - **Informations conditionnelles** selon le type :
     - **Résidentiel** : Adresse, Chambres, Salles de bain, Surface
     - **Terrain** : Adresse, Surface, Type de document
     - **Commercial** : Adresse, Surface
     - **Service** : Prix "À partir de..." (si disponible)
   - **Bouton d'action** :
     - Service : "Demander un devis" (orange)
     - Autres : "Voir les détails" (orange) - Ouvre le modal de détails

5. **Interactions** :
   - Hover : Ombre augmentée, translation vers le haut (-8px)
   - Clic sur "Voir les détails" : Ouvre le modal avec les détails complets

### 3.4 Section Services (Nos Services)
**ID** : `#services`
**Localisation** : `resources/views/welcome.blade.php` (ligne ~1024)

#### Caractéristiques :
- **Titre** : "Nos Services" avec double soulignement décoratif
- **Grille** : 1 colonne (mobile), 2 colonnes (tablette), 4 colonnes (desktop)
- **13 Services affichés** :

1. **Locations de biens immobiliers**
   - Fond carte : `#86c14f` (vert)
   - Fond icône : `#f3a43e` (orange)

2. **Ventes de biens immobiliers**
   - Fond carte : `#f3a43e` (orange)
   - Fond icône : `#352f30` (gris foncé)

3. **Promotion immobilière**
   - Fond carte : `#726961` (gris)
   - Fond icône : `#87c04f` (vert clair)

4. **Etat des lieux**
   - Fond carte : `#86c14f` (vert)
   - Fond icône : `#f3a43e` (orange)

5. **Gestion de biens immobiliers**
   - Fond carte : `#f3a43e` (orange)
   - Fond icône : `#352f30` (gris foncé)

6. **Elaboration de contrat de location**
   - Fond carte : `#726961` (gris)
   - Fond icône : `#87c04f` (vert clair)

7. **Conseil Immobilier**
   - Fond carte : `#86c14f` (vert)
   - Fond icône : `#f3a43e` (orange)

8. **Rénovation et achèvement des biens immobiliers**
   - Fond carte : `#f3a43e` (orange)
   - Fond icône : `#352f30` (gris foncé)

9. **Service de nettoyage**
   - Fond carte : `#726961` (gris)
   - Fond icône : `#87c04f` (vert clair)

10. **Service de transport**
    - Fond carte : `#86c14f` (vert)
    - Fond icône : `#f3a43e` (orange)

11. **Frigoriste-SOS-24/7**
    - Fond carte : `#f3a43e` (orange)
    - Fond icône : `#352f30` (gris foncé)

12. **Plomberie-SOS-24/7**
    - Fond carte : `#726961` (gris)
    - Fond icône : `#87c04f` (vert clair)

13. **Electricité-SOS-24/7**
    - Fond carte : `#86c14f` (vert)
    - Fond icône : `#f3a43e` (orange)

**Style des cartes** :
- Fond coloré selon le service
- Icône SVG blanche dans un cadre arrondi coloré
- Titre en gras
- Description en blanc, gras
- Hover : Ombre augmentée, translation vers le haut, rotation de l'icône
- Animations : Fade-in-up avec délais progressifs

### 3.5 Section Confiance (Présentation)
**ID** : `#confiance`
**Localisation** : `resources/views/welcome.blade.php` (ligne ~1182)

#### Caractéristiques :
- **Layout** : 2 colonnes (image à gauche, texte à droite sur desktop)
- **Image** : Photo de l'équipe (placeholder Unsplash)
- **Contenu** :
  - Titre : "Pourquoi choisir AT Logement ?"
  - Description : Présentation de l'entreprise (10+ ans d'expérience)
  - **4 Points forts** avec icônes :
    1. Expertise locale approfondie du marché guinéen
    2. Accompagnement personnalisé à chaque étape
    3. Portfolio varié de biens sélectionnés avec soin
    4. Transparence totale dans toutes les transactions
- **Interactions** : Hover sur les points forts change la couleur de l'icône

---

## 4. MODALS ET INTERACTIONS

### 4.1 Modal Détails Listing
**Localisation** : `resources/views/welcome.blade.php` (ligne ~576)

#### Fonctionnalités :
- **Ouverture** : Via bouton "Voir les détails" sur une carte d'annonce
- **Contenu affiché** :
  1. **Header** :
     - Titre de l'annonce
     - Bouton fermer (X)
  2. **Prix** : Badge orange avec formatage français (si disponible)
  3. **Adresse** : Avec icône de localisation (si disponible)
  4. **Carrousel d'images** :
     - Navigation précédent/suivant
     - Indicateurs de position
     - Support images et vidéos
  5. **Description complète**
  6. **Caractéristiques** (selon le type) :
     - Résidentiel : Chambres, Salles de bain, Surface
     - Terrain : Surface, Type de document
     - Commercial : Surface
  7. **Champs personnalisés** (`custom_fields`) : Si disponibles
  8. **Réseaux sociaux** : Liens vers Facebook, LinkedIn, Instagram, WhatsApp, Twitter, TikTok (si disponibles)
  9. **Bouton "Envoyer un message"** : Ouvre le formulaire de contact
  10. **Bouton "Retour"** : Ferme le modal

#### Interactions :
- Fermeture : Clic en dehors, touche Escape, bouton fermer
- Navigation images : Boutons précédent/suivant, indicateurs cliquables
- Scroll : Modal scrollable si le contenu dépasse la hauteur de l'écran

### 4.2 Modal Formulaire de Contact
**Localisation** : `resources/views/welcome.blade.php` (ligne ~854)

#### Fonctionnalités :
- **Ouverture** : Via bouton "Envoyer un message" dans le modal de détails
- **Champs du formulaire** :
  1. **Nom complet** * (requis)
  2. **Email** * (requis, validation email)
  3. **Téléphone** * (requis)
  4. **Message** * (requis, textarea)
- **Boutons** :
  - "Fermer" (gris)
  - "Envoyer" (orange) - Désactivé pendant l'envoi
- **Validation** : Validation HTML5 + validation côté serveur
- **Soumission** : POST vers `/messages` (route `messages.store`)

### 4.3 Modal de Succès
**Localisation** : `resources/views/welcome.blade.php` (ligne ~950)

#### Fonctionnalités :
- **Affichage** : Après envoi réussi d'un message
- **Contenu** :
  - Icône de succès (checkmark vert)
  - Titre : "Message envoyé !"
  - Message : "Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais."
  - Bouton "Fermer" (orange)
- **Style** :
  - Largeur maximale : 5cm sur grands écrans, adaptatif sur mobile
  - z-index : 9999 (au-dessus de tout)
  - Centré à l'écran
- **Comportement** :
  - Apparition avec délai de 150ms après la fermeture du formulaire
  - Fermeture : Clic en dehors, touche Escape, bouton fermer
  - Verrouillage du bouton "Envoyer" pendant l'envoi

---

## 5. FONCTIONNALITÉS DE RECHERCHE

### 5.1 Barre de Recherche Principale
**Localisation** : Section Hero (ligne ~216)

#### Caractéristiques :
- **Champ de recherche** : Recherche textuelle libre
- **Portée de recherche** : Titre, description, adresse, ville
- **Soumission** : Formulaire GET vers `/listings/search`

### 5.2 Page de Recherche
**Route** : `/listings/search`
**Vue** : `resources/views/listings/index.blade.php`
**Contrôleur** : `app/Http/Controllers/ListingController.php`

#### Filtres disponibles :
1. **Recherche textuelle** : Titre, description, adresse, ville
2. **Localisation** : Dropdown (Conakry, Kindia, Kankan, Nzérékoré, Labé)
3. **Type de bien** : Dropdown (Résidentiel, Commercial, Terrain, Service)
4. **Nombre de chambres** : Minimum (1+, 2+, 3+, 4+, 5+)
5. **Budget Min** : Input numérique (GNF)
6. **Budget Max** : Input numérique (GNF)

#### Résultats :
- **Pagination** : 12 résultats par page
- **Tri** :
  1. Annonces mises en avant (`is_featured = true`) par `updated_at` (décroissant)
  2. Puis par `published_at` (décroissant)
  3. Puis par `created_at` (décroissant)
- **Affichage** : Grille de cartes identiques à la page d'accueil
- **Scroll automatique** : Vers la section résultats si des paramètres de recherche sont présents

---

## 6. GESTION DES MÉDIAS

### 6.1 Images
- **Stockage** : `storage/app/public/listings/`
- **Compression** : Automatique lors de l'upload
  - Qualité : 85%
  - Taille max : 1920px (largeur ou hauteur)
  - Format : Conversion en WebP si possible
- **Affichage** :
  - Thumbnail : Première image du tableau `images`
  - Carrousel : Toutes les images dans le modal
- **Fallback** : Placeholder SVG si aucune image

### 6.2 Vidéos
- **Formats supportés** : MP4, QuickTime, AVI, WebM
- **Taille max** : 100MB
- **Compression** : Via FFmpeg (si disponible)
- **Affichage** : Dans le carrousel du modal de détails

---

## 7. INTERACTIONS ALPINE.JS

### 7.1 État Global (`alpineData`)
**Localisation** : `resources/views/welcome.blade.php` (ligne ~1327)

#### Variables d'état :
- `showFilters` : Affichage de la section filtres
- `showAnnouncementModal` : Affichage du modal d'annonce
- `showListingModal` : Affichage du modal de détails listing
- `showMessageForm` : Affichage du formulaire de contact
- `showSuccessModal` : Affichage du modal de succès
- `messageSent` : État d'envoi du message
- `isSendingMessage` : Verrouillage du bouton d'envoi
- `currentAnnouncement` : Données de l'annonce actuelle
- `currentListing` : Données du listing actuel
- `currentImageIndex` : Index de l'image actuelle dans le carrousel
- `messageForm` : Données du formulaire de contact
- `searchQuery` : Requête de recherche
- `filters` : Valeurs des filtres

#### Méthodes :
- `openListing(listingData)` : Ouvre le modal de détails avec les données du listing
- `closeListing()` : Ferme le modal de détails
- `openMessageForm()` : Ouvre le formulaire de contact
- `closeMessageForm()` : Ferme le formulaire de contact
- `sendMessage()` : Envoie le message via AJAX
- `applyFilters()` : Applique les filtres et redirige vers la page de recherche
- `getListingImage()` : Retourne l'URL de l'image actuelle
- `nextImage()` : Passe à l'image suivante
- `prevImage()` : Passe à l'image précédente

---

## 8. STYLES ET ANIMATIONS

### 8.1 Palette de Couleurs
- **Orange principal** : `#f97316` / `orange-500`
- **Vert** : `#86c14f` / `#87c04f`
- **Orange secondaire** : `#f3a43e`
- **Gris foncé** : `#352f30` / `#726961`
- **Gris** : `gray-50` à `gray-900`
- **Blanc** : `white`
- **Noir** : `black`

### 8.2 Animations CSS
- **Fade-in-up** : Apparition depuis le bas avec fade
- **Fade-in-scale** : Apparition avec zoom
- **Slide-in-left/right** : Glissement depuis la gauche/droite
- **Zoom-banner** : Zoom lent de l'image de bannière (20s)
- **Hover effects** : Translation, ombre, scale, rotation

### 8.3 Transitions
- **Durée standard** : 300ms
- **Easing** : `ease-out`, `ease-in-out`
- **Propriétés animées** : `transform`, `opacity`, `shadow`, `color`

---

## 9. ACCESSIBILITÉ ET SEO

### 9.1 Accessibilité
- **Navigation clavier** : Support complet (Tab, Escape, Enter)
- **ARIA labels** : Sur les boutons et éléments interactifs
- **Contraste** : Respect des ratios WCAG
- **Focus visible** : Indicateurs de focus sur tous les éléments interactifs

### 9.2 SEO
- **Meta tags** : Titre, description, charset, viewport
- **Structure sémantique** : Utilisation de `<header>`, `<main>`, `<section>`, `<footer>`
- **Alt text** : Tous les images ont un attribut `alt`
- **URLs propres** : Routes nommées, slugs pour les listings

---

## 10. PERFORMANCES

### 10.1 Optimisations
- **Images** : Compression automatique, format WebP
- **Vidéos** : Compression via FFmpeg
- **Lazy loading** : Pour les images (via le navigateur)
- **CDN** : Utilisation de Storage::url() pour les assets

### 10.2 Chargement
- **Vite** : Build et optimisation des assets
- **Fonts** : Preconnect vers fonts.bunny.net
- **Alpine.js** : Chargement asynchrone

---

## 11. ROUTES PUBLIQUES

### 11.1 Routes Principales
- `GET /` : Page d'accueil (`home`)
- `GET /listings/search` : Page de recherche (`listings.search`)
- `GET /listings/{slug}` : Page de détail d'un listing (`listings.show`)
- `POST /messages` : Envoi d'un message (`messages.store`)
- `GET /storage/listings/{path}` : Service d'images (`listing.image`)

---

## 12. DONNÉES AFFICHÉES

### 12.1 Listing (Annonce)
**Modèle** : `app/Models/Listing.php`

#### Champs affichés :
- `title` : Titre de l'annonce
- `slug` : URL-friendly identifier
- `description` : Description complète
- `price` : Prix (optionnel, formaté en GNF)
- `currency` : Devise (défaut : GNF)
- `type` : Type de bien (residential, commercial, land, service)
- `service_status` : Statut du service (recherche, propose, realise) - si applicable
- `address` : Adresse
- `city` : Ville
- `bedrooms` : Nombre de chambres
- `bathrooms` : Nombre de salles de bain
- `surface` : Surface en m²
- `document_type` : Type de document (pour terrains)
- `images` : Tableau d'images/vidéos
- `social_links` : Liens réseaux sociaux (JSON)
- `custom_fields` : Champs personnalisés (JSON)
- `is_featured` : Mise en avant
- `published_at` : Date de publication
- `updated_at` : Date de mise à jour

### 12.2 Filtres de Publication
- `status` : Doit être `true`
- `published_at` : Doit être défini et <= maintenant
- Scope `published()` : Appliqué automatiquement

---

## 13. GESTION DES ERREURS

### 13.1 Erreurs Affichées
- **Aucun résultat** : Message "Aucune annonce disponible pour le moment."
- **Erreur d'envoi de message** : Affichage d'un message d'erreur (à implémenter)
- **Image manquante** : Placeholder SVG affiché

---

## 14. FONCTIONNALITÉS FUTURES (À IMPLÉMENTER)

### 14.1 Améliorations Suggérées
- [ ] Favoris / Liste de souhaits
- [ ] Comparaison de biens
- [ ] Partage sur réseaux sociaux
- [ ] Impression de fiche d'annonce
- [ ] Carte interactive avec localisation des biens
- [ ] Notifications par email pour nouvelles annonces
- [ ] Recherche vocale
- [ ] Mode sombre
- [ ] Multilingue (Français, Anglais, etc.)

---

## 15. MAINTENANCE ET ÉVOLUTION

### 15.1 Fichiers Clés à Modifier
- **Vue principale** : `resources/views/welcome.blade.php`
- **Composant carte** : `resources/views/components/listing-card.blade.php`
- **Header** : `resources/views/components/header.blade.php`
- **Contrôleur** : `app/Http/Controllers/ListingController.php`
- **Modèle** : `app/Models/Listing.php`
- **Routes** : `routes/web.php`

### 15.2 Styles
- **CSS principal** : `resources/css/app.css`
- **Tailwind config** : `tailwind.config.js`
- **Styles inline** : Dans `welcome.blade.php` (section `<style>`)

---

## 16. NOTES TECHNIQUES

### 16.1 Alpine.js
- Version : 3.x
- Initialisation : Via `x-data="alpineData"`
- Événements : `@click`, `@submit`, `@keydown.escape.window`, `@click.away`

### 16.2 Tailwind CSS
- Configuration : Via `tailwind.config.js`
- Classes personnalisées : `double-underline`, `banner-bg-animated`
- Breakpoints : `sm:`, `md:`, `lg:`

### 16.3 Laravel
- Version : 10.x / 11.x
- Blade : Templates avec directives `@if`, `@foreach`, `@php`
- Storage : Disques `public` et `local`
- Routes : Nommées avec `route()`

---

**Document créé le** : 2026-01-01  
**Version** : 1.0  
**Auteur** : Équipe de développement AT Logement

