<?php
require 'conexaoBD.php';
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área Financeira</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        h1, h2 {
            color: #4CAF50;
        }
        .course-table, .teacher-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .course-table th, .teacher-table th, .course-table td, .teacher-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .course-table th, .teacher-table th {
            background-color: #4CAF50;
            color: white;
            text-align: left;
        }
        .course-table tr:nth-child(even), .teacher-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .course-table tr:hover, .teacher-table tr:hover {
            background-color: #ddd;
        }
        .total-revenue, .total-salary, .total-profit {
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Área Financeira</h1>
    
    <h2>Cursos</h2>
    <!-- Tabela de Cursos -->
    <table class="course-table">
        <tr>
            <th>Curso</th>
            <th>Valor (R$)</th>
            <th>Total de Matrículas</th>
            <th>Receita total (R$)</th>
        </tr>
        <!-- Exemplo de linhas de cursos (pode ser dinâmico com PHP) -->
        <tr>
            <td>Curso de PHP</td>
            <td>R$ 200,00</td>
            <td>10</td>
            <td>R$ 2000,00</td>
        </tr>
        <tr>
            <td>Curso de JavaScript</td>
            <td>R$ 250,00</td>
            <td>8</td>
            <td>R$ 2000,00</td>
        </tr>
    </table>
    <div class="total-revenue">
        Receita Total Escolar: R$ 4000,00
    </div>

    <br>
    <h2>Professores</h2>
    <!-- Tabela de Professores Dinâmica -->
    <table class="teacher-table">
        <tr>
            <th>Professor</th>
            <th>Salário (R$)</th>
        </tr>
        <?php
        // Consulta para buscar os professores
        $sql = "SELECT nome, salario FROM professores";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $totalSalarios = 0;
        $totalProfessores = 0;

        while ($professor = $stmt->fetch()) {
            $totalProfessores++;
            $totalSalarios += $professor['salario'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($professor['nome']) . "</td>";
            echo "<td>R$ " . number_format($professor['salario'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <div class="total-salary">
        Total de Professores: <?php echo $totalProfessores; ?> | Total de Salários: R$ <?php echo number_format($totalSalarios, 2, ',', '.'); ?>
    </div>

    <br>
    <h2>Outras Despesas</h2>
    <!-- Tabela de Outras Despesas -->
    <table class="course-table">
        <tr>
            <th>Descrição</th>
            <th>Valor (R$)</th>
        </tr>
        <tr>
            <td>Material Didático</td>
            <td>R$ 500,00</td>
        </tr>
        <tr>
            <td>Manutenção de Equipamentos</td>
            <td>R$ 300,00</td>
        </tr>
        <tr>
            <td>Taxas Administrativas</td>
            <td>R$ 200,00</td>
        </tr>
    </table>
    <div class="total-salary">
        Total de Outras Despesas: R$ 1000,00
    </div>

    <br>
    <h2>Resultado Final</h2>
    <?php
        // Valores totais (substitua com valores dinâmicos conforme necessário)
        $totalReceita = 4000.00; // Receita Total Escolar
        $totalDespesas = $totalSalarios + 1000.00; // Total de Salários + Outras Despesas

        // Cálculo do Lucro
        $lucro = $totalReceita - $totalDespesas;
    ?>
    <div class="total-profit">
        Lucro Total: R$ <?php echo number_format($lucro, 2, ',', '.'); ?>
    </div>

</div>

</body>
</html>
