<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

// Route Accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route Produits (DYNAMIQUE via Base de Données)
Route::get('/produits/{cat}', [ProductController::class, 'getProductsByCategorie'])->name('produits.categorie');

// Routes Statiques
Route::get('/a-propos', [HomeController::class, 'about'])->name('a_propos');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');