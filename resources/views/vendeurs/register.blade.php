@extends('layouts.slaves')

@section('content')
<style>
    :root {
        --primary: #1780d6;
        --secondary: #f4a261;
        --accent: #e76f51;
        --light: #f8f9fa;
        --dark: #264653;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--light);
    }

    /* Vendor Form Section */
    .vendor-section {
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 100px auto 50px auto;
    }
    .vendor-section .card-header {
        background-color: var(--primary);
        color: white;
        font-weight: 600;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 15px;
        text-align: center;
    }
    .vendor-section .card-body {
        padding: 20px;
    }
    .vendor-section .form-label {
        color: var(--dark);
        font-weight: 500;
    }
    .vendor-section .form-control {
        border-radius: 5px;
        transition: border-color 0.3s;
    }
    .vendor-section .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 5px rgba(231, 111, 81, 0.3);
    }
    .vendor-section .btn-primary {
        background-color: var(--secondary);
        border: none;
        width: 100%;
        padding: 10px;
        font-weight: 500;
        transition: background-color 0.3s, transform 0.3s;
    }
    .vendor-section .btn-primary:hover {
        background-color: var(--accent);
        transform: translateY(-2px);
    }
    .vendor-section .error {
        color: var(--accent);
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    /* File Input Styling */
    .vendor-section .custom-file-input {
        position: relative;
        overflow: hidden;
    }
    .vendor-section .custom-file-input input[type="file"] {
        position: absolute;
        top: 0;
        right: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .vendor-section .custom-file-label {
        display: block;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: var(--light);
        color: var(--dark);
        cursor: pointer;
        transition: border-color 0.3s;
    }
    .vendor-section .custom-file-label:hover {
        border-color: var(--accent);
    }

    /* Image Preview */
    .vendor-section .image-preview {
        margin-top: 1rem;
        text-align: center;
    }
    .vendor-section .image-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: none;
    }
    .vendor-section .image-preview .preview-placeholder {
        color: var(--dark);
        font-size: 0.9rem;
        display: block;
    }
    .vendor-section .image-preview .preview-error {
        color: var(--accent);
        font-size: 0.9rem;
        display: none;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .vendor-section {
            padding: 20px;
            margin-top: 80px;
        }
        .vendor-section .card-header {
            font-size: 1.1rem;
        }
        .vendor-section .form-control {
            font-size: 0.9rem;
        }
        .vendor-section .btn-primary {
            font-size: 0.9rem;
        }
        .vendor-section .image-preview img {
            max-height: 150px;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card border-0 vendor-section">
                <div class="card-header">Demande d'inscription vendeur</div>
                <div class="card-body">
                    <form action="{{ route('vendor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="store_name" class="form-label">Nom du magasin</label>
                            <input type="text" name="store_name" id="store_name" value="{{ old('store_name') }}" class="form-control" required>
                            @error('store_name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-control" required>
                            @error('address')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="store_description" class="form-label">Description</label>
                            <textarea name="store_description" id="store_description" class="form-control" rows="4" required>{{ old('store_description') }}</textarea>
                            @error('store_description')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo du magasin (optionnel)</label>
                            <div class="custom-file-input">
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                                <label for="logo" class="custom-file-label">Choisir un fichier</label>
                            </div>
                            @error('logo')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            <div class="image-preview">
                                <img id="logo-preview" src="#" alt="Aperçu du logo">
                                <span class="preview-placeholder">Aucun fichier sélectionné</span>
                                <span class="preview-error">Fichier invalide. Veuillez sélectionner une image (JPEG, PNG, JPG, GIF).</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update file input label and show image preview
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const label = document.querySelector('.custom-file-label');
        const previewImg = document.getElementById('logo-preview');
        const placeholder = document.querySelector('.preview-placeholder');
        const error = document.querySelector('.preview-error');

        // Reset preview
        previewImg.style.display = 'none';
        placeholder.style.display = 'block';
        error.style.display = 'none';
        label.textContent = file ? file.name : 'Choisir un fichier';

        // Check if file is an image
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else if (file) {
            error.style.display = 'block';
            placeholder.style.display = 'none';
        }
    });
</script>

@endsection