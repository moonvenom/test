<?php
include('../inc/fonction_index.php');

$emplois = getStatistiquesEmplois($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques des emplois</title>
    <link href="../assets/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="mb-4 text-center">Statistiques des emplois</h1>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-dark text-center">
            <tr>
                <th>Poste</th>
                <th>Nombre d'hommes</th>
                <th>Nombre de femmes</th>
                <th>Salaire moyen (€)</th>
            </tr>
        </thead>
        <tbody class="text-center">
        <?php if (empty($emplois)): ?>
            <tr><td colspan="4" class="text-muted">Aucune donnée disponible.</td></tr>
        <?php else: ?>
            <?php foreach ($emplois as $emploi): ?>
                <tr>
                    <td><?= htmlspecialchars($emploi['title']) ?></td>
                    <td><?= $emploi['nb_hommes'] ?></td>
                    <td><?= $emploi['nb_femmes'] ?></td>
                    <td><?= $emploi['salaire_moyen'] ?> €</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">← Retour à l'accueil</a>
    </div>
</div>
<script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
