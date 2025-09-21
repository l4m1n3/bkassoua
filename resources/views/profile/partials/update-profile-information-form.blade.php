<section class="container mt-4">
    <header>
        <h2 class="text-lg fw-bold text-dark">Informations sur le profil</h2>
        <p class="text-muted">Mettez à jour les informations de votre profil et votre adresse e-mail.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Nom et Prénom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-dark">
                        Votre adresse e-mail n'est pas vérifiée.
                        <button form="send-verification" class="btn btn-link p-0 small text-primary">
                            Cliquez ici pour renvoyer l'email de vérification.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success small mt-1">Un nouveau lien de vérification a été envoyé à votre adresse e-mail.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>

            @if (session('status') === 'profile-updated')
                <p class="text-success small mb-0">Modifications enregistrées.</p>
            @endif
        </div>
    </form>
</section>
