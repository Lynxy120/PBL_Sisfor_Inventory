<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Login - Sistem Inventori UMKM" />
    <meta name="author" content="Kelompok 6" />
    <title>Login - Grosiran Ayah</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
        }

        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .login-card .card-header {
            background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            border-radius: 15px 15px 0 0 !important;
            padding: 2rem;
        }

        .login-card .card-header h3 {
            color: white;
            margin: 0;
        }

        .btn-login {
            background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(78, 84, 200, 0.4);
        }

        .form-floating .form-control:focus {
            border-color: #4e54c8;
            box-shadow: 0 0 0 0.25rem rgba(78, 84, 200, 0.25);
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card login-card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header text-center">
                                    <i class="fas fa-boxes fa-3x mb-3" style="color: white;"></i>
                                    <h3 class="font-weight-light">Grosiran Ayah</h3>
                                    <p class="text-white-50 mb-0">Sistem Manajemen Inventori</p>
                                </div>
                                <div class="card-body p-4">
                                    <div id="alertContainer"></div>
                                    <form id="loginForm">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputUsername" type="text" placeholder="Username" required />
                                            <label for="inputUsername"><i class="fas fa-user me-2"></i>Username</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" type="password" placeholder="Password" required />
                                            <label for="inputPassword"><i class="fas fa-lock me-2"></i>Password</label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                            <label class="form-check-label" for="inputRememberPassword">Ingat saya</label>
                                        </div>
                                        <div class="d-grid">
                                            <button class="btn btn-login btn-primary btn-lg" type="submit" id="btnLogin">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3 bg-light">
                                    <div class="small text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Kelompok 6 &copy; <?= date('Y') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="js/api.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if already logged in
            if (isLoggedIn()) {
                window.location.href = 'index.php';
            }

            document.getElementById('loginForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const username = document.getElementById('inputUsername').value;
                const password = document.getElementById('inputPassword').value;
                const btnLogin = document.getElementById('btnLogin');
                const alertContainer = document.getElementById('alertContainer');

                // Disable button
                btnLogin.disabled = true;
                btnLogin.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                try {
                    const response = await login(username, password);

                    if (response.status === 'success') {
                        alertContainer.innerHTML = `
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>Login berhasil! Mengalihkan...
                            </div>
                        `;

                        // Give localStorage time to be set, then redirect
                        setTimeout(() => {
                            window.location.href = 'index.php';
                        }, 500);
                    } else {
                        throw new Error(response.message || 'Login gagal');
                    }
                } catch (error) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>${error.message || 'Username atau password salah'}
                        </div>
                    `;

                    btnLogin.disabled = false;
                    btnLogin.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Login';
                }
            });
        });
    </script>
</body>

</html>