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
    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('Produits.create');
    }

    /**
     * Enregistre un nouveau produit
     */
    public function store(\App\Http\Requests\AddProductRequest $request)
    {
        // 1. Initialisation de Cloudinary
        $cloudinary = new \Cloudinary\Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);

        // 2. Upload de l'image
        $uploadedFile = $request->file('image')->getRealPath();
        $uploadResult = $cloudinary->uploadApi()->upload($uploadedFile, [
            'folder' => 'ameziane_store_products'
        ]);

        // 3. Récupération de l'URL sécurisée
        $imageUrl = $uploadResult['secure_url'];

        // 4. Création du produit en Base de Données
        Product::create([
            'nom' => $request->input('nom'),
            'prix' => $request->input('prix'),
            'categorie' => $request->input('categorie'),
            'desc' => $request->input('desc'),
            'image' => $imageUrl
        ]);

        // 5. Redirection avec les détails pour le Modal "Godly"
        return redirect()->route('produits.create')->with([
            'success' => 'Produit ajouté avec succès !',
            'product_name' => $request->input('nom'),
            'product_image' => $imageUrl
        ]);
    }
}
