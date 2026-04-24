# 📋 Guide de Mapping des Champs Make.com → Facebook

Ce guide vous explique comment remplir les champs du formulaire Facebook dans Make.com en utilisant les données reçues du webhook.

## 🔄 Structure des Données du Webhook

Quand une annonce est publiée, le webhook reçoit ces données dans l'objet `inscription` :

```json
{
  "inscription": {
    "titre": "✨ Sublime Villa Contemporaine avec Piscine - Kipé",
    "description": "Découvrez cette villa d'exception...",
    "prix_formate": "2.500.000.000 GNF",
    "adresse": "Kipé Centre, Conakry",
    "URL": "https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1200&q=80"
  }
}
```

## 📝 Mapping des Champs dans Make.com

### 1. **Connexion Facebook** (Connexion *)
- **Type** : Sélection de connexion
- **Action** : Sélectionnez votre connexion Facebook existante
- **Valeur** : `AT_logement_My Facebook` (ou le nom de votre connexion)
- **Note** : Ce champ est déjà configuré, ne nécessite pas de mapping

### 2. **Page Facebook** (Page *)
- **Type** : ID de page
- **Action** : Entrez l'ID de votre page Facebook
- **Valeur** : `108416505631762` (votre ID de page)
- **Note** : Ce champ est statique, ne nécessite pas de mapping depuis le webhook

### 3. **Photos** (Photos *)
- **Type** : URL d'image
- **Action** : Cliquez sur le champ et sélectionnez depuis le webhook
- **Mapping** : `{{1.URL}}` ou `{{inscription.URL}}`
- **Explication** : Utilisez la valeur du champ `URL` du webhook
- **Format attendu** : URL complète de l'image (ex: `https://images.unsplash.com/...`)

### 4. **Légende de la publication** (Légende de la publication)
- **Type** : Texte multiligne
- **Action** : Construisez une légende attractive en combinant plusieurs champs
- **Mapping recommandé** : Utilisez un module "Set variable" ou "Text parser" pour construire :

```
{{1.titre}}

{{1.description}}

📍 {{1.adresse}}
💰 Prix : {{1.prix_formate}}

🔗 Voir plus : {{1.lien_web}}

#Immobilier #Conakry #Guinée #ATLogement
```

**Ou en format plus simple** :
```
{{1.titre}}

{{1.description}}

📍 {{1.adresse}}
💰 {{1.prix_formate}}

{{1.lien_web}}
```

## 🎯 Étapes Détaillées dans Make.com

### Étape 1 : Configurer le champ "Photos"

1. Cliquez sur le champ **"Photos"** dans le module Facebook
2. Dans le menu déroulant, sélectionnez **"Map"** ou **"Add data"**
3. Cliquez sur **"Add item"** ou **"Add field"**
4. Sélectionnez depuis le webhook : `{{1.URL}}` ou `{{inscription.URL}}`
5. Le champ devrait maintenant afficher l'URL de l'image

### Étape 2 : Configurer la "Légende de la publication"

**Option A : Utiliser directement les champs du webhook**

1. Cliquez sur le champ **"Légende de la publication"**
2. Utilisez cette formule (copiez-collez) :

```
{{1.titre}}

{{1.description}}

📍 Localisation : {{1.adresse}}
💰 Prix : {{1.prix_formate}}

🔗 En savoir plus : {{1.lien_web}}

#Immobilier #Conakry #Guinée #ATLogement
```

**Option B : Utiliser un module "Text parser" pour plus de contrôle**

1. Ajoutez un module **"Text parser"** entre le webhook et Facebook
2. Configurez-le pour construire la légende avec les champs du webhook
3. Utilisez la sortie du Text parser dans le champ "Légende"

### Étape 3 : Vérifier les Paramètres Avancés

1. Cliquez sur **"Paramètres avancés"** (si nécessaire)
2. Vérifiez que :
   - **Visibilité** : Public (recommandé)
   - **Planification** : Immédiate (ou selon vos besoins)

## 📊 Exemple de Légende Complète

Voici un exemple de légende bien formatée qui utilise tous les champs :

```
✨ Sublime Villa Contemporaine avec Piscine - Kipé

Découvrez cette villa d'exception offrant des prestations haut de gamme. Salon spacieux, cuisine équipée et jardin arboré. Un havre de paix au cœur de Conakry.

📍 Localisation : Kipé Centre, Conakry
💰 Prix : 2.500.000.000 GNF

🔗 Voir l'annonce complète : https://at-logement.com/listings/villa-kipé

#Immobilier #Luxe #Conakry #Vente #ATLogement #Guinée
```

## 🔧 Résolution de Problèmes

### Problème : Le champ "Photos" ne s'affiche pas
**Solution** :
- Vérifiez que l'URL est complète (commence par `https://`)
- Assurez-vous que l'image est accessible publiquement
- Testez l'URL dans un navigateur avant de l'utiliser

### Problème : La légende est vide ou mal formatée
**Solution** :
- Vérifiez la syntaxe des variables : `{{1.champ}}` ou `{{inscription.champ}}`
- Utilisez un module "Text parser" pour mieux contrôler le formatage
- Testez avec des données réelles depuis le webhook

### Problème : Les emojis ne s'affichent pas
**Solution** :
- Assurez-vous que l'encodage est en UTF-8
- Testez avec des emojis simples d'abord (📍, 💰, 🔗)

## ✅ Checklist de Configuration

- [ ] Connexion Facebook configurée et testée
- [ ] ID de page Facebook correctement renseigné
- [ ] Champ "Photos" mappé avec `{{1.URL}}`
- [ ] Légende de publication construite avec les champs du webhook
- [ ] Test effectué avec une annonce réelle
- [ ] Publication testée sur Facebook (en mode brouillon si possible)

## 🎨 Améliorations Possibles

### Ajouter des Hashtags Dynamiques
Vous pouvez créer un module qui génère des hashtags automatiquement selon :
- Le type d'annonce (Villa, Appartement, Terrain, etc.)
- La localisation (Conakry, Kipé, Ratoma, etc.)
- Le prix (Luxe, Abordable, etc.)

### Ajouter des Caractéristiques
Si vous voulez inclure plus de détails (chambres, surface, etc.), vous devrez modifier le format du webhook pour inclure ces informations.

---

**Note** : Les noms de champs dans Make.com peuvent varier selon votre configuration. Adaptez ce guide selon votre interface exacte.
