<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bilfen İşe Giriş Evrak Takip Sistemi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>
            .login-box {
                max-width: 400px;
            }

            .login-box h5 {
                font-weight: 500;
            }

            .text-center p {
                margin-top: 20px;
                font-size: 0.875rem;
            }

            .form-control {
                width: 80%;
            }

            button:disabled {
                background-color: #ccc;
                cursor: not-allowed;
            }

            .flag-icon {
                width: 20px;
                height: 15px;
                background-size: cover;
            }

            .flag-icon-tr {
                background-image: url('https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/flags/4x3/tr.svg');
            }

            .flag-icon-en {
                background-image: url('https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/flags/4x3/gb.svg');
            }
        </style>
    </head>
    <body>
        <div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="background-color: #02558b">
            <div class="bg-white rounded shadow p-5 text-center login-box">
                <div class="alert alert-danger" role="alert" style="display: none"></div>
                <img src="/logo.jpg" width="100%" alt="Logo"/>
                <h6 id="header">İşe Giriş Evrak Takip Sistemi</h6>

                <form id="loginForm" method="post">
                    @csrf
                    <div class="form-group d-flex mt-5">
                        <label for="email" class="w-25" id="lblEmail">Email</label>
                        <input type="email" id="email" name="email" class="form-control w-75" placeholder="Email bilginizi giriniz" required/>
                    </div>

                    <div class="form-group d-flex mt-3">
                        <label for="password" class="w-25" id="lblPassword">Parola</label>
                        <input type="password" id="password" name="password" class="form-control w-75" placeholder="Parola bilginizi giriniz" required/>
                    </div>

                    <button id="btnLogin" type="submit" class="btn btn-primary mt-4 w-100" style="background-color: #02558b">Giriş Yap</button>
                </form>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">
    const header = document.getElementById("header");
    const lblPassword = document.getElementById("lblPassword");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const btnLogin = document.getElementById("btnLogin");

    window.onload = async () => {
        debugger;

        if(Cookies.get('isAuthenticated')) {
            window.location.href = `/dashboard?userId=${ Cookies.get('userId') }`;
        }
    };

    document.getElementById("loginForm").addEventListener("submit", async function (event) {
        debugger;
        event.preventDefault();
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            const res = await axios.post('http://localhost:8000/api/users/Login', {
                Email: email,
                Password: password
            });

            if (res.data.message != null) {
                document.getElementsByClassName("alert-danger")[0].style.display="block";
                document.getElementsByClassName("alert-danger")[0].innerHTML = res.data.message;
            } else {
                Cookies.set("token", res.data.token, {expires: 1 / 24});
                Cookies.set("isAuthenticated", res.data.isAuthenticated, {
                    expires: 1 / 24
                });
                Cookies.set("userId", res.data.userId, {expires: 1 / 24});
                window.location.href = `/dashboard?userId=${res.data.userId}`;
            }
        } catch (err) {
            console.error(err.message);
        }
    });

    const getCookie = (name) => {
        debugger;
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
</script>
