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
        // Pagination : 6 produits par page
        $products = Product::where('categorie', $cat)->paginate(6);

        if ($products->isEmpty()) {
            abort(404);
        }

        // On passe 'liste' et 'titre'
        return view('Produits', [
            'titre' => ucfirst($cat),
            'liste' => $products
        ]);
    }
}
