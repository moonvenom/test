<?php
include('../inc/fonction_index.php');
// Récupération des filtres depuis l'URL
$filters = [
    'dept_name' => $_GET['dept_name'] ?? '',
    'emp_name' => $_GET['emp_name'] ?? '',
    'age_min' => $_GET['age_min'] ?? '',
    'age_max' => $_GET['age_max'] ?? ''
];

$departements = getDepartements($pdo, $filters);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Départements</title>
    <link href="../assets/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="mb-4 text-center">Recherche dans les départements</h1>

    <!-- FORMULAIRE DE RECHERCHE -->
<form method="get" class="row g-3 mb-5" id="search-form">
    <div class="col-md-3">
        <input type="text" name="dept_name" id="dept_name" class="form-control" placeholder="Nom du département"
               list="deptSuggestions" autocomplete="off"
               value="<?= htmlspecialchars($filters['dept_name']) ?>">
        <datalist id="deptSuggestions"></datalist>
    </div>
    <div class="col-md-3">
        <input type="text" name="emp_name" id="emp_name" class="form-control" placeholder="Nom du manager"
               list="empSuggestions" autocomplete="off"
               value="<?= htmlspecialchars($filters['emp_name']) ?>">
        <datalist id="empSuggestions"></datalist>
    </div>
    <div class="col-md-2">
        <input type="number" name="age_min" class="form-control" placeholder="Âge min"
               value="<?= htmlspecialchars($filters['age_min']) ?>">
    </div>
    <div class="col-md-2">
        <input type="number" name="age_max" class="form-control" placeholder="Âge max"
               value="<?= htmlspecialchars($filters['age_max']) ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Rechercher</button>
    </div>

    <a href="emploie.php">employés</a>
</form>

    

    <!-- TABLEAU DES DEPARTEMENTS -->
<table class="table table-hover table-bordered shadow-sm">
    <thead class="table-dark text-center">
        <tr>
            <th>Nom du département</th>
            <th>Manager</th>
            <th>Nombre d'employés</th> <!-- Nouvelle colonne -->
        </tr>
    </thead>
    <tbody class="text-center">
    <?php if (count($departements) === 0): ?>
        <tr><td colspan="3" class="text-muted">Aucun résultat trouvé.</td></tr>
    <?php else: ?>
        <?php foreach ($departements as $row): ?>
            <tr>
                <td><a href="departement.php?dept_no=<?= $row['dept_no'] ?>" class="text-decoration-none"><?= htmlspecialchars($row['dept_name']) ?></a></td>
                <td><?= htmlspecialchars($row['manager']) ?></td>
                <td><?= $row['nb_employes'] ?></td> <!-- Affichage du nombre -->
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>
<script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
hvvvyhvhgcghchgnchgcnbcn
