# 🚀 AMEZIANE-STORE V3.1 (MVC + Pagination + Data) - Ateliers 6, 7, 8

Ce fichier `README.md` documente la version complète **V3.1** du projet E-commerce.
Cette version intègre une architecture MVC, une pagination robuste, un catalogue étendu avec données réelles, et une navigation dynamique.

---

## 📋 Nouveautés (Ateliers 6, 7, 8)

### Atelier 6 : Architecture MVC

- **Controllers** : Séparation logique via `ProductController` et `HomeController`.
- **Refactoring** : Routes propres pointant vers les méthodes de contrôleur.

### Atelier 7 : Pagination et Catalogue Étendu

- **Catalogue** : Expansion à **72 produits** (9 produits x 8 catégories).
- **Pagination** : Implémentation de `paginate(6)` pour une navigation fluide.
- **Menu Dynamique** : Dropdown "Catégories" compatible Desktop/Mobile (Alpine.js).

### Atelier 8 : Données Réelles et Landing Page

- **Pages Catégories** : Vue dédiée `/categories` présentant les 8 familles de produits.
- **Données Externes** : Utilisation de `products-pictures.json` pour la gestion facile des images.
- **Design** : Intégration complète du thème "Dark Gaming" (Neon/Violet).

---

## 📂 Structure du Code

### 1️⃣ Gestion des Données (`database/seeders`)

L'importation des produits se fait désormais via un fichier JSON externe pour faciliter la maintenance des images.

**Fichier de configuration :** `products-pictures.json`

```json
{
    "consoles": [
        { "name": "PS5 Pro", "img": "https://..." },
        ...
    ]
}
```

**Commande de mise à jour :**

```bash
php artisan db:seed --class=ProductSeeder
```

### 2️⃣ Contrôleurs (`app/Http/Controllers`)

**ProductController :** Gère l'affichage paginé.

```php
public function getProductsByCategorie($cat)
{
    $products = Product::where('categorie', $cat)->paginate(6); // 6 par page
    return view('Produits', ['titre' => ucfirst($cat), 'liste' => $products]);
}
```

**HomeController :** Gère les pages statiques et l'index des catégories.

```php
public function categories()
{
    // Affiche la grille des 8 catégories
    return view('Categories');
}
```

### 3️⃣ Vues Clés (`resources/views`)

- **`Categories.blade.php`** : Nouvelle vue grille pour l'accès visuel aux collections.
- **`Menu.blade.php`** : Navigation responsive avec menu déroulant intelligent.
- **`Produits.blade.php`** : Inclut désormais les liens de pagination Laravel stylisés.

---

## 🌍 Déploiement (AlwaysData + Vercel)

La connexion à la base de données se fait via les variables d'environnement Vercel :

- `DB_CONNECTION`: `mysql`
- `DB_HOST`: `mysql-[user].alwaysdata.net`
- `DB_DATABASE`: `[nom_base]`
- `DB_USERNAME`: `[user]`
- `DB_PASSWORD`: `[password]`

---
*Généré pour documentation externe et analyse IA.*
