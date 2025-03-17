<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Arca do Aventureiro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/style.css"> <!--css template-->
</head>
<body>
    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <h6 class="mb-0 pb-3"><span>Login </span><span>Registro</span></h6>
                        <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
                        <label for="reg-log"></label>
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                
                                <!-- Login -->
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Login</h4>
                                            <form action="../conection/login_process.php" method="POST">
                                                <div class="form-group">
                                                    <input type="email" name="email" class="form-style" placeholder="Email" required>
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>    
                                                <div class="form-group mt-2">
                                                    <input type="password" name="senha" class="form-style" placeholder="Senha" required>
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" class="btn mt-4">Login</button>
                                            </form>
                                            <p class="mb-0 mt-4 text-center"><a href="" class="link">Esqueceu sua senha?</a></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Registro -->
                                <div class="card-back">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-3 pb-3">Registro</h4>
                                            <form action="../conection/registro_process.php" method="POST">
                                                <div class="form-group">
                                                    <input type="text" name="nome_usuario" class="form-style" placeholder="Nome de usuário" required>
                                                    <i class="input-icon uil uil-user"></i>
                                                </div>    
                                                <div class="form-group mt-2">
                                                    <input type="email" name="email" class="form-style" placeholder="Email" required>
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="senha" class="form-style" placeholder="Senha" required>
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" class="btn mt-4">Registrar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
