<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Links Of CSS File -->
    <link rel="stylesheet" href="{{asset('assetss/css/sidebar-menu.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/simplebar.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/apexcharts.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/prism.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/rangeslider.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/sweetalert.min.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/quill.snow.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/google-icon.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/remixicon.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/fullcalendar.main.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/style.css')}}">

    <!-- Favicon -->
    <link href="{{asset('assets/img/logo.png')}}" rel="icon">
    <!-- Title -->
    <title>B Kassoua - Register</title>
    <style>
        .otp-status {
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-warning {
            color: #ffc107;
        }

        #sendOtpBtn[disabled] {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="boxed-size bg-white">
    <!-- Start Preloader Area -->
    <div class="preloader" id="preloader">
        <div class="preloader">
            <div class="waviy position-relative">
                <span class="d-inline-block">B</span>
                <span class="d-inline-block">K</span>
                <span class="d-inline-block">A</span>
                <span class="d-inline-block">S</span>
                <span class="d-inline-block">S</span>
                <span class="d-inline-block">O</span>
                <span class="d-inline-block">U</span>
                <span class="d-inline-block">A</span>
            </div>
        </div>
    </div>
    <!-- End Preloader Area -->

    <!-- Start Main Content Area -->
    <div class="container">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto m-1230">
                <div class="row align-items-center">
                    <div class="col-lg-6 d-none d-lg-block">
                        <img src="{{asset('assets/img/logo.png')}}" class="rounded-3" alt="register">
                    </div>
                    <div class="col-lg-6">
                        <div class="mw-480 ms-lg-auto">

                            <!-- ... (contenu précédent inchangé jusqu'au formulaire) ... -->

                            <h3 class="fs-28 mb-2">Inscrivez-vous sur B Kassoua</h3>
                            <form method="POST" action="{{ route('register') }}" id="registerForm">
                                @csrf

                                <div class="form-floating mb-3 mt-3">
                                    <label class="label text-secondary">Nom et prenom</label>
                                    <input type="text" class="form-control" name="name" placeholder="Entrer votre nom et prenom" required>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                                    <label class="label text-secondary">Email Address</label>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="90 xx xx xx" required>
                                    <label class="label text-secondary">Numero de telephone</label>
                                    <span id="phoneError" class="otp-status text-danger"></span>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <input type="text" name="address" class="form-control" placeholder="Entrer votre adresse" required>
                                    <label class="label text-secondary">Adresse</label>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <input type="password" name="password" class="form-control" placeholder="votre mot de passe" required>
                                    <label class="label text-secondary">Mot de passe</label>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer votre mot de passe" required>
                                    <label class="label text-secondary">Confirmer Mot de passe</label>
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary fw-medium py-2 px-3 w-100" id="submitBtn">
                                        <div class="d-flex align-items-center justify-content-center py-1">
                                            <i class="material-symbols-outlined text-white fs-20 me-2">person_4</i>
                                            <span>S'inscrire et envoyer le code</span>
                                        </div>
                                    </button>
                                </div>
                            </form>

                            <!-- ... (contenu suivant inchangé) ... -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Start Main Content Area -->

    <!-- ... (rest of your HTML remains the same) ... -->

    <!-- Link Of JS File -->
    <script src="{{asset('assetss/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assetss/js/sidebar-menu.js')}}"></script>
    <script src="{{asset('assetss/js/dragdrop.js')}}"></script>
    <script src="{{asset('assetss/js/rangeslider.min.js')}}"></script>
    <script src="{{asset('assetss/js/sweetalert.js')}}"></script>
    <script src="{{asset('assetss/js/quill.min.js')}}"></script>
    <script src="{{asset('assetss/js/data-table.js')}}"></script>
    <script src="{{asset('assetss/js/prism.js')}}"></script>
    <script src="{{asset('assetss/js/clipboard.min.js')}}"></script>
    <script src="{{asset('assetss/js/feather.min.js')}}"></script>
    <script src="{{asset('assetss/js/simplebar.min.js')}}"></script>
    <script src="{{asset('assetss/js/apexcharts.min.js')}}"></script>
    <script src="{{asset('assetss/js/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('assetss/js/fullcalendar.main.js')}}"></script>
    <script src="{{asset('assetss/js/custom/apexcharts.js')}}"></script>
    <script src="{{asset('assetss/js/custom/custom.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sendOtpBtn = document.getElementById('sendOtpBtn');
            const sendOtpText = document.getElementById('sendOtpText');
            const sendOtpSpinner = document.getElementById('sendOtpSpinner');
            const verifyOtpBtn = document.getElementById('verifyOtpBtn');
            const verifyOtpText = document.getElementById('verifyOtpText');
            const verifyOtpSpinner = document.getElementById('verifyOtpSpinner');
            const otpSection = document.getElementById('otpSection');
            const otpStatus = document.getElementById('otpStatus');
            const phoneError = document.getElementById('phoneError');
            const phoneNumberInput = document.getElementById('phone_number');
            const otpInput = document.getElementById('otp');
            const otpVerifiedInput = document.getElementById('otpVerified');
            const submitBtn = document.getElementById('submitBtn');
            const registerForm = document.getElementById('registerForm');

            let otpCountdown;
            let remainingTime = 120; // 2 minutes in seconds

            // // Format phone number as user types
            // phoneNumberInput.addEventListener('input', function(e) {
            //     let value = e.target.value.replace(/\D/g, '');
            //     if (value.length > 0) {
            //         value = value.match(/(\d{0,2})(\d{0,2})(\d{0,2})(\d{0,2})/);
            //         value = value[1] + (value[2] ? ' ' + value[2] : '') + 
            //                 (value[3] ? ' ' + value[3] : '') + 
            //                 (value[4] ? ' ' + value[4] : '');
            //     }
            //     e.target.value = value;
            // });
            phoneNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); // Supprime tout sauf les chiffres

                // Garde seulement les 8 derniers chiffres
                if (value.length > 8) {
                    value = value.slice(-8);
                }

                // Appliquer le format lisible : XX XX XX XX
                let formatted = '';
                if (value.length > 0) {
                    formatted = value.match(/.{1,2}/g)?.join(' ') || '';
                }

                // Ajouter l'indicatif +227
                e.target.value = '+227 ' + formatted.trim();
            });

            // Envoyer OTP
            sendOtpBtn.addEventListener('click', async function() {
                const phoneNumber = phoneNumberInput.value.replace(/\D/g, ''); // Remove all non-digit chars

                if (phoneNumber.length < 10) {
                    phoneError.textContent = 'Veuillez entrer un numéro de téléphone valide (10 chiffres minimum)';
                    return;
                }

                try {
                    // Show loading state
                    sendOtpBtn.disabled = true;
                    sendOtpText.textContent = 'Envoi en cours...';
                    sendOtpSpinner.classList.remove('d-none');
                    phoneError.textContent = '';

                    const response = await fetch('/send-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            phone: phoneNumber
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Échec de l\'envoi du code');
                    }

                    if (data.success) {
                        otpSection.style.display = 'block';
                        otpStatus.textContent = 'Code OTP envoyé! Vérifiez votre téléphone.';
                        otpStatus.className = 'otp-status text-success';
                        startOtpCountdown();
                    } else {
                        throw new Error(data.message || 'Échec de l\'envoi du code');
                    }
                } catch (error) {
                    otpStatus.textContent = 'Erreur: ' + error.message;
                    otpStatus.className = 'otp-status text-danger';
                    console.error('Error:', error);
                } finally {
                    sendOtpBtn.disabled = false;
                    sendOtpText.textContent = 'Renvoyer OTP';
                    sendOtpSpinner.classList.add('d-none');
                }
            });

            // Vérifier OTP
            verifyOtpBtn.addEventListener('click', async function() {
                const otp = otpInput.value.trim();

                if (!otp || otp.length !== 6) {
                    otpStatus.textContent = 'Veuillez entrer un code OTP valide (6 chiffres)';
                    otpStatus.className = 'otp-status text-danger';
                    return;
                }

                try {
                    // Show loading state
                    verifyOtpBtn.disabled = true;
                    verifyOtpText.textContent = 'Vérification...';
                    verifyOtpSpinner.classList.remove('d-none');

                    const response = await fetch('/verify-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            otp: otp
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Échec de la vérification');
                    }

                    if (data.success) {
                        otpVerifiedInput.value = '1';
                        otpStatus.textContent = 'Numéro vérifié avec succès!';
                        otpStatus.className = 'otp-status text-success';
                        submitBtn.disabled = false;
                        clearInterval(otpCountdown);
                    } else {
                        throw new Error(data.message || 'Code OTP invalide');
                    }
                } catch (error) {
                    otpStatus.textContent = 'Erreur: ' + error.message;
                    otpStatus.className = 'otp-status text-danger';
                    console.error('Error:', error);
                } finally {
                    verifyOtpBtn.disabled = false;
                    verifyOtpText.textContent = 'Vérifier OTP';
                    verifyOtpSpinner.classList.add('d-none');
                }
            });

            // Start OTP countdown
            function startOtpCountdown() {
                clearInterval(otpCountdown);
                remainingTime = 120;
                updateCountdownDisplay();

                otpCountdown = setInterval(() => {
                    remainingTime--;
                    updateCountdownDisplay();

                    if (remainingTime <= 0) {
                        clearInterval(otpCountdown);
                        otpStatus.textContent = 'Le code OTP a expiré. Veuillez en demander un nouveau.';
                        otpStatus.className = 'otp-status text-danger';
                    }
                }, 1000);
            }

            function updateCountdownDisplay() {
                const minutes = Math.floor(remainingTime / 60);
                const seconds = remainingTime % 60;
                otpStatus.textContent = `Code valide pour ${minutes}:${seconds.toString().padStart(2, '0')}`;
                otpStatus.className = 'otp-status text-warning';
            }

            // Empêcher la soumission du formulaire si OTP non vérifié
            registerForm.addEventListener('submit', function(e) {
                if (otpVerifiedInput.value !== '1') {
                    e.preventDefault();
                    otpStatus.textContent = 'Veuillez vérifier votre numéro de téléphone avant de soumettre';
                    otpStatus.className = 'otp-status text-danger';
                    // Scroll to OTP section
                    otpSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });
        });
    </script>
</body>

</html>