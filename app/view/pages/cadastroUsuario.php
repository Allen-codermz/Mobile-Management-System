<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cadastro.css">
    <title>Cadastro | Sistema de Gestão de Celulares</title>
</head>

<body>
    <div class="main">
        <div class="esquerda">
            <img src="../images/sign-up-animate.svg" alt="">
        </div>
        <div class="direita">
            <form class="form" id="cadastro">
                <h2><b>Criar conta</b></h2>
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" placeholder="Anacleto Das Dorres">

                <label for="nome">Username</label>
                <input type="text" id="nome" placeholder="AnacletoAgenteSecreto">

                <label for="nome">Contacto</label>
                <input type="text" id="nome" placeholder="+258 84 1234 567">

                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Anacleto@gmail.com">

                <label for="email">Perfil</label>
                <select>
                    <option value="">Selecione o perfil</option>
                    <option value="option1">Operador</option>
                    <option value="option2">Super-Operador</option>
                    <option value="option3">Administrador</option>
                    <option value="option4">Auditor</option>
                </select>

                <div class="pw">
                    <label for="pw">Palavra-passe</label>
                    <input type="password" name="pw" id="pw" placeholder="••••••">

                    <!-- <label for="pw"> Confirmar palavra-passe</label>
                    <input type="password" name="pw" id="pw" placeholder="••••••"> -->
                </div>
                <div class="actions">
                    <button type="submit" class="btn1" form="cadastro">Criar conta</button>
                </div>
            </form>
        </div>

    </div>
</body>

</html>