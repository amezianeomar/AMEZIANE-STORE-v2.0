# 🚀 AMEZIANE-STORE V2.0 (Dynamic Edition)

Ce fichier `README.md` documente la version **V2.0** du projet E-commerce (Atelier 5).
Cette version introduit une **base de données dynamique** (MySQL) hébergée sur AlwaysData, remplaçant les données statiques de la V1.

---

## 📋 Nouveautés V2.0 (Atelier 5)

La mise à jour majeure concerne le passage au **Dynamique** :

- **Base de Données** : MySQL (Hébergé sur AlwaysData).
- **ORM** : Eloquent (Modèle `Product`).
- **Déploiement Hybride** : Application sur Vercel ↔ Base de données sur AlwaysData.

### 🛠 Stack Technique V2

- **Backend** : Laravel 10/11.
- **Database** : MySQL (Production: AlwaysData / Local: Laragon/Wamp).
- **Frontend** : Tailwind CSS + Alpine.js (conservé de la V1).
- **Hébergement** : Vercel (Serverless Functions).

---

## 📂 Code Source (Architecture Dynamique)

Voici les fichiers clés modifiés pour la gestion dynamique des données.

### 1️⃣ Le Modèle (`app/Models/Product.php`)

Représentation Eloquent de la table `products`.

```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['nom', 'prix', 'image', 'desc', 'categorie'];
}
```

### 2️⃣ Routes Dynamiques (`routes/web.php`)

Récupération des produits depuis la BDD via Eloquent (`Product::where(...)`).

```php
<?php
use Illuminate\Support\Facades\Route;
use App\Models\Product; 

Route::get('/', function () {
    return view('Home');
})->name('home');

Route::get('/produits/{cat}', function ($cat) {
    // Requete SQL dynamique
    $products = Product::where('categorie', $cat)->get();

    if ($products->isEmpty()) {
        abort(404);
    }

    return view('Produits', [
        'titre' => ucfirst($cat),
        'liste' => $products
    ]);
})->name('produits.categorie');

// Routes Statiques
Route::get('/a-propos', function () { return view('A_propos'); })->name('a_propos');
Route::get('/contact', function () { return view('Contact'); })->name('contact');
```

### 3️⃣ Seeder de Données (`database/seeders/ProductSeeder.php`)

Script utilisé pour peupler la base de données (localement et en production).

```php
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nom' => 'PS5 Slim',
                'prix' => 549,
                'image' => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?auto=format&fit=crop&w=600&q=80',
                'desc' => 'La nouvelle PS5, plus fine, même puissance.',
                'categorie' => 'consoles'
            ],
            // ... autres produits (Xbox, Switch, Claviers...)
        ];

        foreach ($data as $item) {
            Product::create($item);
        }
    }
}
```

### 4️⃣ Vue Dynamique (`resources/views/Produits.blade.php`)

Boucle `@foreach` sur les objets Eloquent récupérés.

```html
@extends('Master_page')

@section('content')
<div class="mb-8 md:mb-12 text-center md:text-left px-2">
    <h2 class="font-display font-bold text-3xl md:text-4xl text-white mb-4">
        CATÉGORIE <span class="text-brand-neon">/</span> {{ strtoupper($titre) }}
    </h2>
</div>

@if(count($liste) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($liste as $produit)
            <div class="group bg-brand-surface rounded-xl overflow-hidden border border-white/5">
                <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="w-full h-56 object-cover">
                <div class="p-5">
                    <h3 class="font-display text-xl font-bold text-white">{{ $produit->nom }}</h3>
                    <p class="text-gray-400 text-sm mb-4">{{ $produit->desc }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-brand-neon font-bold">{{ $produit->prix }} DHS</span>
                        <button class="bg-white/5 hover:bg-brand-magenta text-white px-4 py-2 rounded uppercase text-sm font-bold transition-colors">
                            Ajouter
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-gray-400 text-center">Aucun produit trouvé.</p>
@endif
@endsection
```

### 5️⃣ Configuration Déploiement (`vercel.json`)

Identique à la V1, compatible avec l'architecture Serverless.

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
