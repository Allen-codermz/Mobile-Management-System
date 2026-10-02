<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Login | Sistema de Gestão de Celulares</title>
    <link rel='stylesheet' href='../css/recuperar.css'>
</head>

<body>
    <div class="main">
        <div class="esquerda">
            <img src="../images/login-animate.svg" alt="">
        </div>
        <div class="direita">
            <form class="form" id="cadastro">
                <h2><b>Vamos alterar a sua senha?</b></h2>
                <label for="nome">Username</label>
                <input type="text" id="nome" placeholder="Jane Doe">

                <label for="email">Senha antiga</label>
                <input type="password" id="email" placeholder="••••••">

                <div class="pw">
                    <label for="pw">Senha nova</label>
                    <input type="password" name="pw" id="pw" placeholder="••••••">
                </div>
                <div class="actions">
                    <button type="submit" class="btn1" form="cadastro">Recuperar</button>
                </div>
            </form>

        </div>

    </div>

</body>

</html>