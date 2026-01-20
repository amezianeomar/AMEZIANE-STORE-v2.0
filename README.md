# 🚀 AMEZIANE-STORE V3.0 (Architecture MVC) - Atelier 6

Ce fichier `README.md` documente la version **V3.0** du projet E-commerce (Atelier 6).
Cette version introduit une **Architecture MVC complète** (Model-View-Controller) pour structurer le code de manière professionnelle.

---

## 📋 Nouveautés V3.0 (Atelier 6)

L'objectif principal était de séparer la logique de traitement des routes :

- **Controllers** : Introduction de `ProductController` et `HomeController`.
- **Refactoring** : Remplacement des closures anonymes par des appels de méthodes de contrôleur.
- **MVC** : Architecture respectée (Model `Product` ↔ Controller `ProductController` ↔ View `Produits`).

### 🛠 Stack Technique V3

- **Backend** : Laravel 10/11 (Architecture MVC).
- **Database** : MySQL (Production: AlwaysData / Local: Laragon/Wamp).
- **Frontend** : Tailwind CSS + Alpine.js.
- **Hébergement** : Vercel.

---

## 📂 Code Source (Architecture MVC)

Voici les fichiers clés introduits pour la gestion MVC.

### 1️⃣ Le Controller Principal (`app/Http/Controllers/ProductController.php`)

Gère la logique métier pour les produits.

```php
<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductsByCategorie($cat)
    {
        $products = Product::where('categorie', $cat)->get();

        if ($products->isEmpty()) { abort(404); }

        return view('Produits', [
            'titre' => ucfirst($cat),
            'liste' => $products
        ]);
    }
}
```

### 2️⃣ Routes MVC (`routes/web.php`)

Les routes délèguent désormais le traitement aux contrôleurs.

```php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

// Routes dynamiques via Controller
Route::get('/produits/{cat}', [ProductController::class, 'getProductsByCategorie'])->name('produits.categorie');

// Routes statiques via HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/a-propos', [HomeController::class, 'about'])->name('a_propos');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
```

### 3️⃣ Le Modèle (`app/Models/Product.php`)

Demeure inchangé, reponsable de l'interaction avec la base de données.

```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['nom', 'prix', 'image', 'desc', 'categorie'];
}
```

```json
{
    "version": 2,
    "outputDirectory": "public",
    "functions": { "api/index.php": { "runtime": "vercel-php@0.7.1" } },
    "routes": [
        { "src": "/build/(.*)", "dest": "/build/$1" },
        { "src": "/assets/(.*)", "dest": "/assets/$1" },
        { "src": "/favicon.png", "dest": "/favicon.png" },
        { "src": "/(.*)", "dest": "/api/index.php" }
    ]
}
```

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
