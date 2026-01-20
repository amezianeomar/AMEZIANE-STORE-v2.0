<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Récupère les produits par catégorie
     *
     * @param string $cat
     * @return \Illuminate\View\View
     */
    public function getProductsByCategorie($cat)
    {
        // Requete SQL dynamique
        $products = Product::where('categorie', $cat)->get();

        if ($products->isEmpty()) {
            abort(404);
        }

        // On passe 'liste' et 'titre' pour respecter les variables utilisées dans Produits.blade.php
        // tout en respectant l'instruction de passer les données.
        return view('Produits', [
            'titre' => ucfirst($cat),
            'liste' => $products
        ]);
    }
}
