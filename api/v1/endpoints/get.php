<?php

/*
 * Trata as requisições GET
 */

function assistidos() {
    $con = getConexao();
    $token = (isset($_SERVER['HTTP_TOKEN'])) ? $_SERVER['HTTP_TOKEN'] : null;

    try {
        $id_usuario = pegarUsuarioPeloToken($token);
    } catch (Exception $e) {
        return prepararRetorno($e->getCode(), $e->getMessage());
    }

    $sql = "select f.*, uf.*, g.nome nome_genero from filmes as f";
    $sql .= " inner join usuarios_filmes as uf on (uf.id_filme = f.id) ";
    $sql .= " inner join generos g on (g.id = f.id_genero) ";

    $where = array(
        "uf.assistido = 1",
        "(id_usuario = " . $con->quote($id_usuario, PDO::PARAM_INT) . ")",
    );

    if ($where) {
        $sql .= " where " . join(' and ', $where);
    }

    $query = $con->query($sql);

    $retorno = array();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $filme = array(
            "id" => $row['id'],
            "titulo" => $row['titulo'],
            "lancamento" => $row['lancamento'],
            "sinopse" => $row['sinopse'],
            "favorito" => (bool) $row['favorito'],
            "assistido" => (bool) $row['assistido'],
            "nota" => (int) $row['nota'],
            "comentario" => $row['comentario'],
            "nome_genero" => $row['nome_genero'],
            "imagens" => array(),
        );

        $sql_imagens = "select url from filmes_imagens where id_filme = " . $row['id'] . " order by ordem ASC";
        $imagens = $con->query($sql_imagens)->fetchAll(PDO::FETCH_COLUMN);

        if ($imagens) {
            $filme["imagens"] = $imagens;
        }

        $retorno[] = $filme;
    }

    return prepararRetorno(null, null, $retorno);
}

function avaliados() {
    $con = getConexao();
    $token = (isset($_SERVER['HTTP_TOKEN'])) ? $_SERVER['HTTP_TOKEN'] : null;

    try {
        $id_usuario = pegarUsuarioPeloToken($token);
    } catch (Exception $e) {
        return prepararRetorno($e->getCode(), $e->getMessage());
    }

    $sql = "select f.*, uf.*, g.nome nome_genero from filmes as f";
    $sql .= " inner join usuarios_filmes as uf on (uf.id_filme = f.id) ";
    $sql .= " inner join generos g on (g.id = f.id_genero) ";

    $where = array(
        "uf.nota is not null",
        "(id_usuario = " . $con->quote($id_usuario, PDO::PARAM_INT) . ")",
    );

    if ($where) {
        $sql .= " where " . join(' and ', $where);
    }

    $query = $con->query($sql);

    $retorno = array();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $filme = array(
            "id" => $row['id'],
            "titulo" => $row['titulo'],
            "lancamento" => $row['lancamento'],
            "sinopse" => $row['sinopse'],
            "favorito" => (bool) $row['favorito'],
            "assistido" => (bool) $row['assistido'],
            "nota" => (int) $row['nota'],
            "comentario" => $row['comentario'],
            "nome_genero" => $row['nome_genero'],
            "imagens" => array(),
        );

        $sql_imagens = "select url from filmes_imagens where id_filme = " . $row['id'] . " order by ordem ASC";
        $imagens = $con->query($sql_imagens)->fetchAll(PDO::FETCH_COLUMN);

        if ($imagens) {
            $filme["imagens"] = $imagens;
        }

        $retorno[] = $filme;
    }

    return prepararRetorno(null, null, $retorno);
}

function favoritos() {
    $con = getConexao();
    $token = (isset($_SERVER['HTTP_TOKEN'])) ? $_SERVER['HTTP_TOKEN'] : null;

    try {
        $id_usuario = pegarUsuarioPeloToken($token);
    } catch (Exception $e) {
        return prepararRetorno($e->getCode(), $e->getMessage());
    }

    $sql = "select f.*, uf.*, g.nome nome_genero from filmes as f";
    $sql .= " inner join usuarios_filmes as uf on (uf.id_filme = f.id) ";
    $sql .= " inner join generos g on (g.id = f.id_genero) ";

    $where = array(
        "uf.favorito = 1",
        "(id_usuario = " . $con->quote($id_usuario, PDO::PARAM_INT) . ")"
    );

    $sql .= " where " . join(' and ', $where);

    $query = $con->query($sql);

    $retorno = array();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $filme = array(
            "id" => $row['id'],
            "titulo" => $row['titulo'],
            "lancamento" => $row['lancamento'],
            "sinopse" => $row['sinopse'],
            "favorito" => (bool) $row['favorito'],
            "assistido" => (bool) $row['assistido'],
            "nota" => (int) $row['nota'],
            "comentario" => $row['comentario'],
            "nome_genero" => $row['nome_genero'],
            "imagens" => array(),
        );

        $sql_imagens = "select url from filmes_imagens where id_filme = " . $row['id'] . " order by ordem ASC";
        $imagens = $con->query($sql_imagens)->fetchAll(PDO::FETCH_COLUMN);

        if ($imagens) {
            $filme["imagens"] = $imagens;
        }

        $retorno[] = $filme;

    }

    return prepararRetorno(null, null, $retorno);
}

/**
 * Retorna a lista de filmes
 */
function filmes() {
    $con = getConexao();

    $sql = "select f.*, g.nome as nome_genero, round(avg(nota)) as nota, sum(if(nota is not null, 1, 0)) qtde_avaliacoes, sum(if(comentario is not null, 1, 0)) qtde_comentarios from filmes as f ";
    $sql .= " left join filmes_imagens fi on (fi.id_filme = f.id) ";
    $sql .= " left join generos g on (g.id = f.id_genero) ";
    $sql .= " left join usuarios_filmes uf on (uf.id_filme = f.id) ";

    $where = array();
    $params = array();

    $req_params = $_GET;

    if (isset($req_params['id'])) {
        $params['id'] = (int) $req_params['id'];
        $where[] = " (f.id = " . $con->quote($params['id'], PDO::PARAM_INT) . ") ";
    }

    if (isset($req_params['titulo'])) {
        $params['titulo'] = trim($req_params['titulo']);
        $where[] = " ((f.titulo = " . $con->quote($params['titulo'], PDO::PARAM_STR) . ") or (f.titulo like " . $con->quote("%{$params['titulo']}%", PDO::PARAM_STR) . ")) ";
    }

    if (isset($req_params['id_genero'])) {
        $params['id_genero'] = (int) $req_params['id_genero'];
        $where[] = " (g.id = " . $con->quote($params['id_genero'], PDO::PARAM_INT) . ") ";
    }

    if (isset($req_params['nome_genero'])) {
        $params['nome_genero'] = trim($req_params['nome_genero']);
        $where[] = " ((g.nome = " . $con->quote($params['nome_genero'], PDO::PARAM_STR) . ") or (g.nome like " . $con->quote("%{$params['nome_genero']}%", PDO::PARAM_STR) . ")) ";
    }

    if ($where) {
        $sql .= " where" . join(" and ", $where);
    }

    $sql .= " group by f.id ";
    $sql .= " order by f.id ASC, fi.ordem ASC ";

    $query = $con->query($sql);

    $retorno = array();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $filme = array(
            "id" => (int) $row['id'],
            "titulo" => $row['titulo'],
            "lancamento" => $row['lancamento'],
            "sinopse" => $row['sinopse'],
            "id_genero" => (int) $row['id_genero'],
            "nome_genero" => $row['nome_genero'],
            "nota" => ($row['nota']) ? (int) $row['nota'] : null,
            "qtde_avaliacoes" => (int) $row['qtde_avaliacoes'],
            "qtde_comentarios" => (int) $row['qtde_comentarios'],
            "imagens" => array(),
        );

        $sql_imagens = "select url from filmes_imagens where id_filme = " . $row['id'] . " order by ordem ASC";
        $imagens = $con->query($sql_imagens)->fetchAll(PDO::FETCH_COLUMN);

        if ($imagens) {
            $filme["imagens"] = $imagens;
        }

        $retorno[] = $filme;
    }

    return prepararRetorno(null, null, $retorno);
}

function filmesComentarios() {
    $con = getConexao();

    if (!isset($_GET['id'])) {
        return prepararRetorno(1, "O parâmetro id é obrigatório.");
    }

    $id = (int) $_GET['id'];

    $sql = "select u.nome as usuario, uf.nota, uf.comentario from usuarios u ";
    $sql .= " inner join usuarios_filmes uf on (uf.id_usuario = u.id) ";
    $sql .= " inner join filmes f on (f.id = uf.id_filme) ";
    $sql .= " where uf.id_filme = " . $id . " and uf.comentario is not null ";

    $comentarios = $con->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return prepararRetorno(null, null, array(
        "qtde_comentarios" => count($comentarios),
        "comentarios" => $comentarios,
    ));
}

/**
 * Retorna a lista de generos de filmes
 */
function generos() {
    $con = getConexao();

    $sql = "select * from generos";
    $where = array();

    $params = array();
    $req_params = $_GET;

    if (isset($req_params['id'])) {
        $params['id'] = (int) $req_params['id'];
        $where[] = " (id = " . $con->quote($params['id'], PDO::PARAM_INT) . ") ";
    }

    if (isset($req_params['nome'])) {
        $params['nome'] = trim($req_params['nome']);
        $where[] = " ((nome = " . $con->quote($params['nome'], PDO::PARAM_STR) . ") or (nome like " . $con->quote("%{$params['nome']}%", PDO::PARAM_STR) . ")) ";
    }

    if ($where) {
        $sql .= " where" . join(" and ", $where);
    }

    $query = $con->query($sql);
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);

    return prepararRetorno(null, null, $rows);
}
