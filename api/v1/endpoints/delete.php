<?php

/*
 * Trata as requisições DELETE
 */

function favoritos() {
    $con = getConexao();
    $token = (isset($_SERVER['HTTP_TOKEN'])) ? $_SERVER['HTTP_TOKEN'] : null;

    try {
        $id_usuario = pegarUsuarioPeloToken($token);
    } catch (Exception $e) {
        return prepararRetorno($e->getCode(), $e->getMessage());
    }

    $req_params = $_GET;

    if (!isset($req_params['id_filme'])) {
        return prepararRetorno(2, "O parâmetro 'id_filme' é obrigatório.");
    } else {
        $id_filme = (int) $req_params['id_filme'];
    }

    $query = $con->prepare("update usuarios_filmes set favorito = 0 where (id_usuario = :id_usuario) and (id_filme = :id_filme)");
    $update_ok = $query->execute(array(
        ":id_usuario" => $id_usuario,
        ":id_filme" => $id_filme,
    ));

    if ($update_ok) {
        return prepararRetorno(null, null);
    } else {
        return prepararRetorno(3, "Não foi possível remover o filme da lista de favoritos do usuário.");
    }
}
