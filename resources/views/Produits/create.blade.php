@extends('Master_page')

@section('content')
<div class="min-h-screen py-6 px-4 sm:py-12 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto bg-brand-surface border border-white/10 rounded-xl shadow-2xl overflow-hidden animate-fade-in-up">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-brand-dark to-brand-surface p-4 sm:p-6 border-b border-white/5">
            <h2 class="text-xl sm:text-2xl font-display font-bold text-white text-center tracking-wider">
                AJOUTER UN <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-neon to-brand-magenta">PRODUIT</span>
            </h2>
            <p class="mt-2 text-center text-xs sm:text-sm text-gray-400">Remplissez les détails pour enrichir le catalogue.</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-500/10 border-l-4 border-green-500 p-4 m-6 mb-0">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-400 font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 space-y-6">
            @csrf

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-300">Nom du Produit</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                    class="mt-1 block w-full px-3 py-2 bg-brand-dark border border-white/10 rounded-md text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-neon focus:border-transparent transition-all duration-300">
                @error('nom')
                    <p class="mt-1 text-xs text-red-400 shadow-[0_0_10px_rgba(255,0,0,0.2)]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prix -->
            <div>
                <label for="prix" class="block text-sm font-medium text-gray-300">Prix (MAD)</label>
                <input type="number" name="prix" id="prix" value="{{ old('prix') }}" required step="0.01"
                    class="mt-1 block w-full px-3 py-2 bg-brand-dark border border-white/10 rounded-md text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-neon focus:border-transparent transition-all duration-300">
                @error('prix')
                    <p class="mt-1 text-xs text-red-400 shadow-[0_0_10px_rgba(255,0,0,0.2)]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catégorie -->
            <div>
                <label for="categorie" class="block text-sm font-medium text-gray-300">Catégorie</label>
                <select name="categorie" id="categorie" required
                    class="mt-1 block w-full px-3 py-2 bg-brand-dark border border-white/10 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-brand-neon focus:border-transparent transition-all duration-300">
                    <option value="" disabled selected>Choisir une catégorie</option>
                    @foreach(['consoles', 'monitors', 'son', 'claviers', 'souris', 'chaises', 'video-games', 'cartes-graphiques'] as $cat)
                        <option value="{{ $cat }}" {{ old('categorie') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
                @error('categorie')
                    <p class="mt-1 text-xs text-red-400 shadow-[0_0_10px_rgba(255,0,0,0.2)]">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="desc" class="block text-sm font-medium text-gray-300">Description</label>
                <textarea name="desc" id="desc" rows="3"
                    class="mt-1 block w-full px-3 py-2 bg-brand-dark border border-white/10 rounded-md text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-neon focus:border-transparent transition-all duration-300">{{ old('desc') }}</textarea>
            </div>

            <!-- Image + Preview -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-300 mb-2">Image du Produit</label>
                
                <!-- Image Preview Container -->
                <div id="image-preview" class="hidden mb-4 rounded-lg overflow-hidden border-2 border-brand-neon/50 shadow-[0_0_15px_rgba(0,0,0,0.5)] relative group">
                    <img id="preview-img" src="#" alt="Aperçu" class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="text-white font-bold tracking-widest text-sm">APERÇU</span>
                    </div>
                </div>

                <input type="file" name="image" id="image" required accept="image/*"
                    onchange="previewImage(event)"
                    class="mt-1 block w-full text-sm text-gray-400
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-brand-neon_20 file:text-brand-neon
                    hover:file:bg-brand-neon_30 cursor-pointer">
                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2Mo.</p>
                @error('image')
                    <p class="mt-1 text-xs text-red-400 shadow-[0_0_10px_rgba(255,0,0,0.2)]">{{ $message }}</p>
                @enderror
            </div>

            <script>
                function previewImage(event) {
                    const reader = new FileReader();
                    const imageField = document.getElementById("image");
                    const previewContainer = document.getElementById("image-preview");
                    const previewImg = document.getElementById("preview-img");

                    reader.onload = function(){
                        if(reader.readyState == 2){
                            previewImg.src = reader.result;
                            previewContainer.classList.remove("hidden");
                        }
                    }

                    if(event.target.files[0]){
                        reader.readAsDataURL(event.target.files[0]);
                    }
                }
            </script>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-brand-neon hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-neon transition-all duration-300 transform hover:scale-[1.02]">
                    AJOUTER LE PRODUIT
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
