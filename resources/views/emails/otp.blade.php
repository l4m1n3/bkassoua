@component('mail::message')
# Bonjour {{ $name ?? 'Cher utilisateur' }},

Votre code de vérification (OTP) est :

**{{ $otp }}**

Ce code est valide pendant **3 minutes**.

**Téléphone associé :** {{ $phone }}

Si vous n'avez pas demandé ce code, ignorez cet email.

Merci,<br>
L'équipe **Bkassoua**
@endcomponent