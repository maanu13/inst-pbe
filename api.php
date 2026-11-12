<?php
//CONFIGURAÇÃO
// DEFINE A RESPOSTA DA API, NESSE CASO EM JSON
header("Content-Type: application/json;charset=utf-8");
// DEFINE O ACESSO À API, NESSE * QUER DIZER TODAS AS ORIGENS
header("Access-Control-Allow-Origin: *");
// DEFINE OS MÉTODOS QUE PODEM SER FEITOS NESSA API
header("Acess-Control-Allow-Methods: GET, POST, PUT, DELETE");

require "config.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];

//VARIÁVEL PARA CAPTURAR AS INFORMAÇÕES DAS REQUISIÇÕES
$corpo = json_decode(file_get_contents("php://input"), true) ?? [];

function teste(){
    echo json_encode(["mensagem" => "Back-end respondendo com sucesso!"]);
    exit;
}

// FUNÇÕES - CRUD
// CREATE USUARIO
function createUsuario($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO usuarios (nome, username, email, senha)
            VALUES (?,?,?,?)"
);
    $stmt ->execute([$dados["nome"],
                    $dados["username"],
                    $dados["email"],
                    $dados["senha"]]);
http_response_code(201);
echo json_encode(
    ["mensagem" => "Usuário criado com sucesso!",
    "id" => $con->lastInsertId()]
    );
}
//READ USUARIO
function readUsuario($con){
    $stmt = $con->query("SELECT * FROM usuarios");
    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
}
//UPDATE USUARIO
function updateUsuario($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE usuarios SET nome = ?, username = ?, email = ?, senha = ?
        WHERE id_usuario = ?"
    );
    $stmt -> execute([  $dados["nome"],
                        $dados["username"],
                        $dados["email"],
                        $dados["senha"],
                        $id]);
    echo json_encode(["mensagem" => "Usuário alterado com sucesso!"]);
}
//DELETE USUARIO
function deleteUsuario($con, $id){
    $stmt= $con-> prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Usuário removido com sucesso!"]);
}
// FUNÇÕES - CRUD
// CREATE PUBLICACOES
function createPublicacoes($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO publicacoes (id_usuario, legenda, imagem_url)
            VALUES (?,?,?)"
);
    $stmt ->execute([$dados["id_usuario"],
                    $dados["legenda"],
                    $dados["imagem_url"]]);
http_response_code(201);
echo json_encode(
    ["mensagem" => "Publicação postada com sucesso!",
    "id" => $con->lastInsertId()]
    );
}
//READ PUBLICACOES
function readPublicacoes($con){
    $stmt = $con->query("SELECT * FROM publicacoes");
    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
}
//UPDATE PUBLICACOES
function updatePublicacoes($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE publicacoes SET legenda = ?
        WHERE id_publicacao = ?"
    );
    $stmt -> execute([$dados["legenda"],
                    $id]);
    echo json_encode(["mensagem" => "Legenda alterada com sucesso!"]);
}
//DELETE PUBLICACOES
function deletePublicacoes($con, $id){
    $stmt= $con-> prepare("DELETE FROM publicacoes WHERE id_publicacao = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Publicação removida com sucesso!"]);
}
// FUNÇÕES - CRUD
// CREATE CURTIDAS
function createCurtidas($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO curtidas (id_usuario, id_publicacao)
            VALUES (?,?)"
);
    $stmt ->execute([$dados["id_usuario"],
                    $dados["id_publicacao"]]);
http_response_code(201);
echo json_encode(
    ["mensagem" => "Curtiu a publicação com sucesso!",
    "id" => $con->lastInsertId()]
    );
}
//READ CURTIDAS
function readCurtidas($con){
    $stmt = $con->query("SELECT * FROM curtidas");
    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
}
//UPDATE CURTIDAS
function updateCurtidas($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE curtidas SET id_usuario = ?
        WHERE id_curtida = ?"
    );
    $stmt -> execute([  $dados["id_usuario"],
                        $id]);
    echo json_encode(["mensagem" => "Curtida alterada com sucesso!"]);
}
//DELETE CURTIDAS
function deleteCurtidas($con, $id){
    $stmt= $con-> prepare("DELETE FROM curtidas WHERE id_curtida = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Curtida removida com sucesso!"]);
}
// ROTAS  NOMEADAS
if ($rota === "usuarios") {
    switch($metodo){
        case "POST":
            createUsuario($con, $corpo);
            break;
        case "GET":
            readUsuario($con);
            break;
        case "PUT":
            updateUsuario($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteUsuario($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}else if ($rota === "publicacoes"){
    switch($metodo){
        case "POST":
            createPublicacoes($con, $corpo);
            break;
        case "GET":
            readPublicacoes($con);
            break;
        case "PUT":
            updatePublicacoes($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deletePublicacoes($con, $_GET["id"]);
            break;
        default:
            teste();        
    }
}else if ($rota === "curtidas"){
    switch($metodo){
        case "POST":
            createCurtidas($con, $corpo);
            break;
        case "GET":
            readCurtidas($con);
            break;
        case "PUT":
            updateCurtidas($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteCurtidas($con, $_GET["id"]);
            break;
        default:
            teste();        
    }
}
