<!doctype html>
<html lang="es">

<head>
    <title>Iniciar Sesión</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f9f3f3;
            font-family: Arial, sans-serif;
        }

        .login-container {
            max-width: 420px;
            margin: 100px auto;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header-title {
            text-align: center;
            font-size: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #6a5d7b 0%, #a497bf 100%);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-header-sub {
            text-align: center;
            background: #eee;
            padding: 20px;
        }

        .card-header-sub img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .form-label,
        .form-text {
            margin-left: 5px;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-login {
            background-color: #6a5d7b;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-size: 16px;
            color: white;
            /* Aseguré que el texto sea blanco */
        }

        .btn-login:hover {
            background-color: #55486a;
            color: white;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 15px;
        }

        /* Nuevo estilo para el enlace de recuperación */
        .forgot-password {
            color: #6a5d7b;
            text-decoration: none;
            font-size: 0.9em;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #4a3d5b;
            text-decoration: underline;
        }
    </style>
</head>


<body>
    <div class="login-container">
        <form method="post" autocomplete="off">
            <div class="card">
                <div class="card-header-title">
                    SUPER ABARROTES AM
                </div>
                <div class="card-header-sub">
                    <img src="/public/imagenes/perfil.png" alt="Logo">
                    <p class="mb-0">Ingresa tus credenciales</p>
                </div>
                <div class="card-body">
                    <?php if ($mensaje): ?>
                        <div class="error-message"><?php echo htmlspecialchars($mensaje); ?></div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['csrf_token'])): ?>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>" />
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" class="form-control"
                            name="username"
                            placeholder="Usuario"
                            required maxlength="50" pattern="[a-zA-Z0-9_]{1,50}" title="Solo letras, números y guiones bajos (máx 50 caracteres)" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control"
                            name="password"
                            placeholder="Contraseña"
                            required
                            minlength="8"
                            maxlength="72"
                            title="La contraseña debe tener entre 8 y 72 caracteres" />
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-login">Iniciar sesión</button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="recuperar.php" class="forgot-password">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>