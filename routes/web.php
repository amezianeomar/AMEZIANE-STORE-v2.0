<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product; // <--- TRES IMPORTANT : Importer le Modèle

// Route Accueil
Route::get('/', function () {
    return view('Home');
})->name('home');

// Route Produits (DYNAMIQUE via Base de Données)
Route::get('/produits/{cat}', function ($cat) {
    
    // On demande à Eloquent : "Trouve-moi les produits où la catégorie est égale à $cat"
    $products = Product::where('categorie', $cat)->get();

    // Si aucun produit n'est trouvé (ex: catégorie 'voitures'), on affiche 404
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