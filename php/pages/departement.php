<?php
include('../inc/fonction_departement.php');

$dept_no = $_GET['dept_no'] ?? null;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

$limit = 20;
$offset = ($page - 1) * $limit;

$total = countEmployesDuDepartement($pdo, $dept_no);
$employes = getEmployesDuDepartement($pdo, $dept_no, $limit, $offset);

if (!$dept_no || !$employes) {
    echo "Département introuvable ou vide.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Employés du département</title>
    <link href="../assets/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="container">
        <h1 class="mb-4 text-center">Employés du département <?= htmlspecialchars($dept_no) ?></h1>
        <a href="index.php" class="btn btn-secondary mb-3">← Retour</a>
        <table class="table table-striped table-bordered shadow-sm">
            <thead class="table-primary text-center">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Poste</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($employes as $emp): ?>
                    <tr>
                        <td colspan="2">
                            <a href="employe.php?emp_no=<?= $emp['emp_no'] ?>" class="text-decoration-none fw-semibold">
                                <?= htmlspecialchars($emp['last_name']) . ' ' . htmlspecialchars($emp['first_name']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($emp['title']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        $totalPages = ceil($total / $limit);
        ?>


<nav aria-label="Pagination" class="d-flex justify-content-center mt-4">
    <ul class="pagination">

        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?dept_no=<?= htmlspecialchars($dept_no) ?>&page=<?= $page - 1 ?>" tabindex="-1">Précédent</a>
        </li>


        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                <a class="page-link" href="?dept_no=<?= htmlspecialchars($dept_no) ?>&page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?dept_no=<?= htmlspecialchars($dept_no) ?>&page=<?= $page + 1 ?>">Suivant</a>
        </li>
    </ul>
</nav>

    </div>
</div>
<script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
