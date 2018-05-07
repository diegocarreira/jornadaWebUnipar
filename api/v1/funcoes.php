<?php

function getConexao() {
    $db = DB_DATABASE;
    $host = DB_HOST;
    $charset = DB_CHARSET;
    $user = DB_USER;
    $password = DB_PASSWORD;

    return new PDO("mysql:dbname={$db};host={$host};charset={$charset}", $user, $password);
}

function getEndpoint() {
    $endpoint = null;
    $matches = array();
    $request_uri = trim($_SERVER['REQUEST_URI'], "/");

    if (preg_match("#^api\/v1\/([^\/\?]+)#", $request_uri, $matches)) {
        $endpoint = $matches[1];
    }

    return $endpoint;
}

function prepararRetorno($cd_erro, $msg_erro, $dados = null) {
    $r = array(
        "erro" => array(
            "cd" => null,
            "msg" => null,
        ),
        "dados" => array(),
    );

    if ($cd_erro) {
        $r["erro"]["cd"] = $cd_erro;
        $r["erro"]["msg"] = $msg_erro;
    }

    if ($dados) {
        $r["dados"] = $dados;
    }

    return json_encode($r);
}

function pegarUsuarioPeloToken($token) {
    $con = getConexao();

    $sql = "select * from usuarios_token where token = :token";
    $query = $con->prepare($sql);
    $query->bindValue(":token", $token);
    $query->execute();

    $row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Token não encontrado.", 1000);
    } else {
        $validade = strtotime($row['validade']);

        if ($validade < time()) {
            throw new Exception("Token expirado.", 1001);
        }
    }

    return $row['id_usuario'];
}
