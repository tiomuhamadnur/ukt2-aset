<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ASET | UKT2.ORG</title>
    <link rel="shortcut icon" href="img/ukt2logo.png" />
    <link rel="stylesheet" href="{{ asset('assets/css/csslogin/style.css') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/img/ukt2logo.png') }}">
</head>

<body>
    <main>
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <form class="sign-in-form" class="login" action="{{ route('login') }}" method="POST">
                        @csrf
                        @method('post')
                        <div class="logo">
                            <h1>Aset | UKT2.ORG</h1>
                            <img src="{{ asset('assets/img/ukt2logo.png') }}" alt="image">
                        </div>
                        <div class="heading">
                            <h2>Selamat Datang,</h2>
                        </div>
                        <div class="actual-form">
                            <div class="input-wrap">
                                <input type="text" minlength="4" name="email" class="input-field"
                                    placeholder="Masukkan Email" required autofocus />
                            </div>
                            @error('email')
                                <span class="badge bg-danger p-2 mb-3" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="input-wrap">
                                <input type="password" name="password" class="input-field" autocomplete="off"
                                    placeholder="Masukkan Password" required />
                            </div>
                            @error('password')
                                <span class="badge badge-danger p-2 mb-3" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="submit" value="Masuk" class="sign-btn" />
                        </div>
                    </form>
                </div>
                <div class="carousel">
                    <div class="images-wrapper">
                        <img src="{{ asset('assets/img/ukt2logo.png') }}" class="image img-1 show" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
