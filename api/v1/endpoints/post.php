<?php

/*
 * Trata as requisições POST
 */

function assistidos() {
    $con = getConexao();
    $token = (isset($_SERVER['HTTP_TOKEN'])) ? $_SERVER['HTTP_TOKEN'] : null;

    try {
        $id_usuario = pegarUsuarioPeloToken($token);
    } catch (Exception $e) {
        return prepararRetorno($e->getCode(), $e->getMessage());
    }

    $req_params = $_POST;

    if (!isset($req_params['id_filme'])) {
        return prepararRetorno(2, "O parâmetro 'id_filme' é obrigatório.");
    } else {
        $id_filme = (int) $req_params['id_filme'];
    }

    $query = $con->prepare("insert into usuarios_filmes (id_usuario, id_filme, assistido) values (:id_usuario, :id_filme, :assistido)");
    $insert_ok = $query->execute(array(
        ":id_usuario" => $id_usuario,
        ":id_filme" => $id_filme,
        ":assistido" => 1,
    ));

    if ($insert_ok) {
        return prepararRetorno(null, null);
    } else {
        $error = $query->errorInfo();

        if ($error[0] != 23000) {
            return prepararRetorno(3, "Não foi possível inserir o filme na lista de assistidos do usuário.");
        }

        $query = $con->prepare("update usuarios_filmes set assistido = :assistido where id_usuario = :id_usuario and id_filme = :id_filme");
        $update_ok = $query->execute(array(
            ":id_usuario" => $id_usuario,
            ":id_filme" => $id_filme,
            ":assistido" => 1,
        ));

        if (!$update_ok) {
            return prepararRetorno(3, "Não foi possível inserir o filme na lista de assistidos do usuário.");
        } else {
            return prepararRetorno(null, null);
        }
    }
}

function avaliados() {
    $con = getConexao();
    $token = (isset($_SERVER['HTTP_TOKEN'])) ? $_SERVER['HTTP_TOKEN'] : null;

    try {
        $id_usuario = pegarUsuarioPeloToken($token);
    } catch (Exception $e) {
        return prepararRetorno($e->getCode(), $e->getMessage());
    }

    $req_params = $_POST;

    if (!isset($req_params['id_filme'])) {
        return prepararRetorno(2, "O parâmetro 'id_filme' é obrigatório.");
    } else {
        $id_filme = (int) $req_params['id_filme'];
    }

    if (!isset($req_params['nota'])) {
        return prepararRetorno(4, "O parâmetro 'nota' é obrigatório.");
    } else {
        $nota = (int) $req_params['nota'];

        if ($nota < 0) {
            return prepararRetorno(5, "A nota deve ser maior ou igual a zero");
        } elseif ($nota > 5) {
            return prepararRetorno(5, "A nota deve ser menor ou igual a cinco");
        }
    }

    if (isset($req_params['comentario'])) {
        $comentario = strip_tags($req_params['comentario']);
    } else {
        $comentario = null;
    }

    $query = $con->prepare("insert into usuarios_filmes (id_usuario, id_filme, nota, comentario) values (:id_usuario, :id_filme, :nota, :comentario)");
    $insert_ok = $query->execute(array(
        ":id_usuario" => $id_usuario,
        ":id_filme" => $id_filme,
        ":nota" => $nota,
        ":comentario" => $comentario,
    ));

    if ($insert_ok) {
        return prepararRetorno(null, null);
    } else {
        $error = $query->errorInfo();

        if ($error[0] != 23000) {
            return prepararRetorno(3, "Não foi possível registrar a avaliação do filme.");
        }

        $query = $con->prepare("update usuarios_filmes set nota = :nota, comentario = :comentario where id_usuario = :id_usuario and id_filme = :id_filme");
        $update_ok = $query->execute(array(
            ":id_usuario" => $id_usuario,
            ":id_filme" => $id_filme,
            ":nota" => $nota,
            ":comentario" => $comentario,
        ));

        if (!$update_ok) {
            return prepararRetorno(3, "Não foi possível registrar a avaliação do filme.");
        } else {
            return prepararRetorno(null, null);
        }
    }
}

function favoritos() {
    $con = getConexao();
    $token = (isset($_SERVER['HTTP_TOKEN'])) ? $_SERVER['HTTP_TOKEN'] : null;

    try {
        $id_usuario = pegarUsuarioPeloToken($token);
    } catch (Exception $e) {
        return prepararRetorno($e->getCode(), $e->getMessage());
    }

    $req_params = $_POST;

    if (!isset($req_params['id_filme'])) {
        return prepararRetorno(2, "O parâmetro 'id_filme' é obrigatório.");
    } else {
        $id_filme = (int) $req_params['id_filme'];
    }

    $query = $con->prepare("insert into usuarios_filmes (id_usuario, id_filme, favorito) values (:id_usuario, :id_filme, :favorito)");
    $insert_ok = $query->execute(array(
        ":id_usuario" => $id_usuario,
        ":id_filme" => $id_filme,
        ":favorito" => 1,
    ));

    if ($insert_ok) {
        return prepararRetorno(null, null);
    } else {
        $error = $query->errorInfo();

        if ($error[0] != 23000) {
            return prepararRetorno(3, "Não foi possível inserir o filme na lista de favoritos do usuário.");
        }

        $query = $con->prepare("update usuarios_filmes set favorito = :favorito where id_usuario = :id_usuario and id_filme = :id_filme");
        $update_ok = $query->execute(array(
            ":id_usuario" => $id_usuario,
            ":id_filme" => $id_filme,
            ":favorito" => 1,
        ));

        if (!$update_ok) {
            return prepararRetorno(3, "Não foi possível inserir o filme na lista de favoritos do usuário.");
        } else {
            return prepararRetorno(null, null);
        }
    }
}

function token() {
    $con = getConexao();

    $req_params = $_POST;

    if (isset($req_params['email'])) {
        $email = $req_params['email'];
    } else {
        return prepararRetorno(1, "O parâmetro email é obrigatório.");
    }

    if (isset($req_params['senha'])) {
        $senha = $req_params['senha'];
    } else {
        return prepararRetorno(2, "O parâmetro senha é obrigatório.");
    }

    $sql = "select u.* from usuarios u where u.email = :email and u.senha = :senha";
    $query = $con->prepare($sql);
    $query->bindValue(":email", $email);
    $query->bindValue(":senha", $senha);
    $query->execute();

    $row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return prepararRetorno(3, "Usuário não encontrado.");
    }

    $randomBytes = openssl_random_pseudo_bytes(16);
    $token = bin2hex($randomBytes);

    $con->prepare("delete from usuarios_token where id_usuario = :id_usuario")->execute(array(":id_usuario" => $row['id']));

    $query = $con->prepare("insert into usuarios_token (id_usuario, token, validade) values (:id_usuario, :token, :validade)");
    $query->bindValue(":id_usuario", $row['id']);
    $query->bindValue(":token", $token);
    $query->bindValue(":validade", date("c", strtotime("+12 hours")));
    $tokenGravado = $query->execute();

    if ($tokenGravado) {
        return prepararRetorno(null, null, array("token" => $token));
    } else {
        return prepararRetorno(4, "Erro para gerar o token.");
    }
}

function usuarios() {
    $con = getConexao();

    $req_params = $_POST;

    if (isset($req_params['email'])) {
        $email = $req_params['email'];
    } else {
        return prepararRetorno(1, "O parâmetro email é obrigatório.");
    }

    if (isset($req_params['senha'])) {
        $senha = $req_params['senha'];
    } else {
        return prepararRetorno(2, "O parâmetro senha é obrigatório.");
    }

    if (isset($req_params['nome'])) {
        $nome = $req_params['nome'];
    } else {
        return prepararRetorno(3, "O parâmetro nome é obrigatório.");
    }

    $query = $con->prepare("insert into usuarios (nome, email, senha) values (:nome, :email, :senha)");
    $query->bindValue(":nome", $nome);
    $query->bindValue(":email", $email);
    $query->bindValue(":senha", $senha);
    $usuarioCadastrado = $query->execute();

    if ($usuarioCadastrado) {
        return prepararRetorno(null, null);
    } else {
        return prepararRetorno(4, "Erro para cadastrar o usuário.");
    }
}
