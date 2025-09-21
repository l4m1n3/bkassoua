<section class="container mt-4">
    <header>
        <h2 class="text-lg font-medium text-dark">Mise à jour du mot de passe</h2>
        <p class="text-muted">Assurez-vous d'utiliser un mot de passe long et sécurisé.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="current_password" class="form-label">Mot de passe actuel</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
            @error('current_password')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
            @error('password')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            @error('password_confirmation')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>

            @if (session('status') === 'password-updated')
                <p class="text-success small mb-0">Mot de passe mis à jour.</p>
            @endif
        </div>
    </form>
</section>
