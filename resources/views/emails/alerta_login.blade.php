<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <style>
        .container {
            font-family: Arial;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn {
            background: blue;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
        }
    </style>

</head>

<body>

    <center>
        <div class="container">
            <div class="content">
                <h1>Nuevo inicio de sesión detectado :o</h1>
                <p>Se ha detectado actividad nueva en tu cuenta.</p><a href="{{ route('login') }}" class="btn"
                    style="color:white;">
                    Ir al sistema </a>
                <p style="margin-top: 20px;">Si no fuiste tu, solicita un cambio <br> de contraseña al adminsitrador.
                </p>
            </div>
        </div>
    </center>

</body>

</html>
