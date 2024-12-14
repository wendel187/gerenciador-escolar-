<?php
function verifica_acesso($tiposPermitidos) {
    if (!isset($_SESSION['usuario_tipo']) || !in_array($_SESSION['usuario_tipo'], $tiposPermitidos)) {
        header("Location: index.php?error=Acesso não autorizado");
        exit();
    }
}
?>