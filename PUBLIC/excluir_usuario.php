
<?php
<<<<<<< HEAD
require_once __DIR__ . "/../INFRA/conexao.php";
require_once __DIR__ . "/../INFRA/verificar_login.php";
require_once __DIR__ . "/../INFRA/verificar_administrador.php";
=======
require_once __DIR__ . "/../infra/conexao.php";
require_once __DIR__ . "/../infra/verificar_login.php";
require_once __DIR__ . "/../infra/verificar_administrador.php";
>>>>>>> 31f87ab6c2de53642b0e42814028b011c64f7e24

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        header("Location: cadastro_usuarios.php");
        exit;
    }

    $stmt = $conexao->prepare(
        "SELECT id FROM usuarios WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        $stmt->close();
        header("Location: cadastro_usuarios.php");
        exit;
    }

    $stmt->close();

    $stmt = $conexao->prepare(
        "DELETE FROM usuarios WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    try {

        if ($stmt->execute()) {
            $stmt->close();

            header("Location: cadastro_usuarios.php");
            exit;
        }

    } catch (mysqli_sql_exception $e) {

        $stmt->close();

        die("Erro ao excluir usuário: " . $e->getMessage());
    }

    $stmt->close();

} else {

    header("Location: cadastro_usuarios.php");
    exit;
}
?>
