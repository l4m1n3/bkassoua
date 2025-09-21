<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Links Of CSS File -->
    <link rel="stylesheet" href="{{asset('assetss/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assetss/css/sweetalert.min.css')}}">

    <!-- Favicon -->
    <link href="{{asset('assets/img/logo.png')}}" rel="icon">
    <title>B Kassoua - Vérification OTP</title>
    <style>
        .otp-status { font-size: 0.9rem; margin-top: 0.5rem; display: block; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        #verifyBtn[disabled], #resendOtpBtn[disabled] { opacity: 0.7; cursor: not-allowed; }
    </style>
</head>
<body class="boxed-size bg-white">
    <!-- Preloader -->
    <!-- <div class="preloader" id="preloader">
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
    </div> -->

    <div class="container">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto m-1230">
                <div class="row align-items-center">
                    <div class="col-lg-6 d-none d-lg-block">
                        <img src="{{asset('assets/img/logo.png')}}" class="rounded-3" alt="verify-otp">
                    </div>
                    <div class="col-lg-6">
                        <div class="mw-480 ms-lg-auto">
                            <h3 class="fs-28 mb-2">Vérification du numéro de téléphone</h3>
                            <p>Un code de vérification a été envoyé à {{ session('register_phone') }}</p>

                            <form id="verifyOtpForm">
                                @csrf
                                <div class="form-floating mb-3 mt-3">
                                    <input type="number" id="otp" name="otp" class="form-control" placeholder="Entrez le code OTP" required>
                                    <label class="label text-secondary">Code de vérification</label>
                                    <button type="button" id="resendOtpBtn" class="btn btn-link p-0 mt-2" disabled>Renvoyer le code</button>
                                </div>
                                <span id="otpStatus" class="otp-status"></span>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary fw-medium py-2 px-3 w-100" id="verifyBtn">
                                        <div class="d-flex align-items-center justify-content-center py-1">
                                            <i class="material-symbols-outlined text-white fs-20 me-2">verified</i>
                                            <span>Vérifier et compléter l'inscription</span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{asset('assetss/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assetss/js/sweetalert.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verifyOtpForm = document.getElementById('verifyOtpForm');
            const otpInput = document.getElementById('otp');
            const otpStatus = document.getElementById('otpStatus');
            const verifyBtn = document.getElementById('verifyBtn');
            const resendOtpBtn = document.getElementById('resendOtpBtn');

            let countdownInterval;
            let remainingTime = 180; // 3 minutes

            // Démarrer le compte à rebours
            startCountdown();

            // Soumission du formulaire OTP
            verifyOtpForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const otp = otpInput.value.trim();

                if (!otp || otp.length !== 6) {
                    otpStatus.textContent = 'Veuillez entrer un code OTP valide (6 chiffres)';
                    otpStatus.className = 'otp-status text-danger';
                    return;
                }

                try {
                    verifyBtn.disabled = true;
                    verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Vérification...';

                    const response = await fetch('{{ route('register.step2') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ otp })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Échec de la vérification');
                    }

                    if (data.success) {
                        otpStatus.textContent = data.message || 'Vérification réussie !';
                        otpStatus.className = 'otp-status text-success';
                        setTimeout(() => {
                            window.location.href = data.redirect || '/';
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Code OTP invalide');
                    }
                } catch (error) {
                    otpStatus.textContent = 'Erreur : ' + error.message;
                    otpStatus.className = 'otp-status text-danger';
                    console.error('Error:', error);
                } finally {
                    verifyBtn.disabled = false;
                    verifyBtn.innerHTML = '<div class="d-flex align-items-center justify-content-center py-1"><i class="material-symbols-outlined text-white fs-20 me-2">verified</i><span>Vérifier et compléter l\'inscription</span></div>';
                }
            });

            // Renvoyer OTP
            resendOtpBtn.addEventListener('click', async function() {
                try {
                    resendOtpBtn.disabled = true;
                    otpStatus.textContent = 'Envoi d\'un nouveau code...';
                    otpStatus.className = 'otp-status text-warning';

                    const response = await fetch('{{ route('resend.otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Échec de l\'envoi');
                    }

                    if (data.success) {
                        otpStatus.textContent = data.message || 'Nouveau code envoyé !';
                        otpStatus.className = 'otp-status text-success';
                        remainingTime = 180;
                        startCountdown();
                    }
                } catch (error) {
                    otpStatus.textContent = 'Erreur : ' + error.message;
                    otpStatus.className = 'otp-status text-danger';
                    console.error('Error:', error);
                }
            });

            function startCountdown() {
                clearInterval(countdownInterval);
                updateCountdownDisplay();

                countdownInterval = setInterval(() => {
                    remainingTime--;
                    updateCountdownDisplay();

                    if (remainingTime <= 0) {
                        clearInterval(countdownInterval);
                        resendOtpBtn.disabled = false;
                        resendOtpBtn.innerHTML = 'Renvoyer le code';
                    }
                }, 1000);
            }

            function updateCountdownDisplay() {
                const minutes = Math.floor(remainingTime / 60);
                const seconds = remainingTime % 60;
                resendOtpBtn.innerHTML = `Renvoyer le code (${minutes}:${seconds.toString().padStart(2, '0')})`;
                resendOtpBtn.disabled = remainingTime > 0;
            }
        });
    </script>
</body>
</html>