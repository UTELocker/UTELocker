<x-auth>
    <div class="container mt-5" style="max-width: 550px">
        <h3 class="text-capitalize mb-4 f-w-500">{{ __('app.verifyPhone') }}</h3>
        <form id="otp-form">
            @csrf
            <div class="form-group text-left">
                <label for="email">{{ __('auth.phone') }}</label>
                <input type="text"
                       class="form-control height-50 f-15 light_text mb-3"
                       autofocus
                       readonly
                       value='{{$phone}}'
                       placeholder="@lang('auth.phone')"
                       id="number"
                       name="phone"
                >
                <input type="hidden" name="email" value="{{user()->email}}">
                <div id="recaptcha-container" class="mb-3"></div>
                <div>
                    Are you still using this phone number? If not, please update your phone number
                    <span class="text-primary mb-3" style="cursor: pointer">
                        <button type="submit" class="btn btn-link p-0">
                            here
                        </button>
                    </span>
                </div>
                <button
                    type="button"
                    class="btn-primary f-w-500 rounded w-100 height-50 f-18 mt-3"
                    onclick="sendOTP();"
                >
                    Send OTP
                </button>
            </div>
        </form>
        <form id="verifi-otp-form" style="display: none">
            <div class="form-group text-left">
                <label for="email">{{ __('auth.verify') }}</label>
                <input type="number"
                       class="form-control height-50 f-15 light_text mb-3"
                       autofocus
                       max="6"
                       placeholder="@lang('auth.verify')" id="verification">
                <div class="mb-3">
                    Your OTP code will expire in <span id="countdown">60</span> seconds <br>
                    <span class="text-secondary mb-3" id="resendOtp">
                        Resend OTP
                    </span>
                </div>
                <div id="recaptcha-container-resend" class="mb-3"></div>
                <button
                    type="button"
                    class="btn-primary f-w-500 rounded w-100 height-50 f-18"
                    onclick="verifyCode();"
                >
                    Verify code
                </button>
            </div>
        </form>
    </div>
    <x-slot name="scripts">
        <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
        <script>
            const firebaseConfig = {
                apiKey: "{{ globalSettings()->firebase_api_key }}",
                authDomain: "{{ globalSettings()->firebase_auth_domain }}",
                projectId: "{{ globalSettings()->firebase_project_id }}",
                storageBucket: "{{ globalSettings()->firebase_storage_bucket }}",
                messagingSenderId: "{{ globalSettings()->firebase_messaging_sender_id }}",
                appId: "{{ globalSettings()->firebase_app_id }}",
                measurementId: "{{ globalSettings()->firebase_measurement_id }}"
            };
            firebase.initializeApp(firebaseConfig);
        </script>
        <script type="text/javascript">
            window.onload = function () {
                render();
            };

            const phoneUser = {!! json_encode($phone) !!};

            function render(idRender = 'recaptcha-container') {
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(idRender);
                recaptchaVerifier.render();
            }

            function addValidation(mess) {
                const html = '<div class="invalid-feedback d-block">' + mess + '</div>';
                $("#otp-form .invalid-feedback").remove();
                $("#otp-form button").before(html);

                $("#verifi-otp-form .invalid-feedback").remove();
                $("#verifi-otp-form button").before(html);
            }

            function validateForm() {
                const phone = $("#number").val();

                if (!phone) {
                    $("#number").css("border", "1px solid red");
                    addValidation("Phone number is required");
                    return false;
                }
                else {
                    $("#number").css("border", "1px solid #ced4da");
                }

                if (phone !== phoneUser) {
                    $("#number").css("border", "1px solid red");
                    addValidation("Phone number is not match");
                    return false;
                }

                else {
                    $("#number").css("border", "1px solid #ced4da");
                }

                if (phone.indexOf("+84") === -1) {
                    if (phone.length !== 10) {
                        $("#number").css("border", "1px solid red");
                        addValidation("Phone number is invalid");
                        return false;
                    }
                    else {
                        $("#number").val("+84" + phone);
                        $("#number").css("border", "1px solid #ced4da");
                    }
                }
                else {
                    if (phone.length !== 12) {
                        $("#number").css("border", "1px solid red");
                        addValidation("Phone number is invalid");
                        return false;
                    }
                    else {
                        $("#number").css("border", "1px solid #ced4da");
                    }
                }

                return true;
            }

            function countdown(seconds) {
                const i = setInterval(function () {
                    $('#countdown').text(--seconds);
                    if (seconds <= 0) {
                        $("#resendOtp").removeClass("text-secondary");
                        $("#resendOtp").addClass("text-primary");
                        $("#resendOtp").css("cursor", "pointer");
                        render('recaptcha-container-resend');
                        clearInterval(i);
                    }
                }, 1000);
            }

            function sendOTP() {
                if (!validateForm()) {
                    return;
                }
                const number = $("#number").val();
                $("#number").val(phoneUser);
                firebase
                    .auth()
                    .signInWithPhoneNumber(number, window.recaptchaVerifier)
                    .then(function (confirmationResult) {
                        window.confirmationResult = confirmationResult;
                        coderesult = confirmationResult;
                        $("#otp-form").hide();
                        $("#verifi-otp-form").show();
                        countdown(60);
                    }).catch(function (error) {
                        addValidation(error.message);
                    });
            }
            function verifyCode() {
                const code = $("#verification").val();
                coderesult.confirm(code).then(function (result) {
                    const user = result.user;
                    const newUrl = "{{ request()->get('url') ?? route('admin.dashboard') }}";
                    $.ajax({
                        url: "{{ route('verify-phone.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            phone: user.phoneNumber
                        },
                        success: function (data) {
                            if (data.status === 'success') {
                                window.location.href = newUrl;
                            }
                        },
                        error: function (error) {
                            addValidation(error.message);
                        }
                    });
                }).catch(function (error) {
                    addValidation(error.message);
                });
            }

            $('#resendOtp').click(function () {
                const typeText = $('#resendOtp').hasClass('text-primary');
                if (typeText) {
                    sendOTP();
                }
            });
        </script>
    </x-slot>
</x-auth>
