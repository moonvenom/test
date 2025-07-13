<?php
include('../inc/fonction_employe.php');

$emp_no = $_GET['emp_no'] ?? null;

if (!$emp_no || !ctype_digit($emp_no)) {
    echo "Aucun employé sélectionné ou identifiant invalide.";
    exit;
}

$emp = getEmployeDetails($pdo, (int)$emp_no);
if (!$emp) {
    echo "Employé introuvable.";
    exit;
}
echo test;

$historiqueSalaires = getHistoriqueSalaires($pdo, (int)$emp_no);
$emploiLePlusLong = getEmploiLePlusLong($pdo, (int)$emp_no);
$historiqueTitres = getHistoriqueTitres($pdo, (int)$emp_no);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de <?= htmlspecialchars($emp['first_name']) ?></title>
    <link href="../assets/bootstrap-5.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="container">
        <h1 class="text-center mb-4">Fiche de l'employé</h1>

        <?php if ($emploiLePlusLong): ?>
            <div class="alert alert-info text-center fw-bold">
                <p>📌 <strong>Poste occupé le plus longtemps :</strong> <?= htmlspecialchars($emploiLePlusLong['title']) ?></p>
                <p class="text-muted mb-0">
                    Du <?= $emploiLePlusLong['from_date'] ?> au <?= $emploiLePlusLong['to_date'] ?? 'présent' ?>
                    (<?= $emploiLePlusLong['duree'] ?> jours)
                </p>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm p-4 mb-5">
            <h4><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></h4>
            <p><strong>Sexe :</strong> <?= $emp['gender'] === 'M' ? 'Homme' : 'Femme' ?></p>
            <p><strong>Date de naissance :</strong> <?= htmlspecialchars($emp['birth_date']) ?></p>
            <p><strong>Date d'embauche :</strong> <?= htmlspecialchars($emp['hire_date']) ?></p>
            <p><strong>Poste actuel :</strong> <?= htmlspecialchars($emp['title'] ?? 'Non disponible') ?></p>
            <p><strong>Département :</strong> <?= htmlspecialchars($emp['dept_name'] ?? 'Non affecté') ?></p>
            <p><strong>Salaire actuel :</strong> <?= $emp['salary'] ? number_format($emp['salary'], 0, ',', ' ') . ' $' : 'Non disponible' ?></p>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <h5>Historique des postes</h5>
            <?php if (count($historiqueTitres) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($historiqueTitres as $titre): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($titre['title']) ?>
                            <br><small class="text-muted">
                                Du <?= $titre['from_date'] ?> au <?= $titre['to_date'] ?? 'présent' ?>
                            </small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Aucun poste enregistré.</p>
            <?php endif; ?>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <h5>Historique des salaires</h5>
            <?php if (count($historiqueSalaires) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($historiqueSalaires as $salaire): ?>
                        <li class="list-group-item">
                            <?= number_format($salaire['salary'], 0, ',', ' ') ?> $
                            <br><small class="text-muted">
                                Du <?= $salaire['from_date'] ?> au <?= $salaire['to_date'] ?>
                            </small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Aucun salaire enregistré.</p>
            <?php endif; ?>
        </div>

        <!-- Formulaire pour changer de département -->
        <button class="btn btn-primary mt-3" data-bs-toggle="collapse" data-bs-target="#form-departement">
            Changer de département
        </button>

        <div class="collapse mt-3" id="form-departement">
            <form action="changer_departement.php" method="post" class="card card-body shadow-sm">
                <input type="hidden" name="emp_no" value="<?= htmlspecialchars($emp['emp_no']) ?>">

                <div class="mb-3">
                    <label for="dept_no" class="form-label">Nouveau département</label>
                    <select class="form-select" name="dept_no" id="dept_no" required>
                        <?php
                        $departements = $pdo->query("SELECT dept_no, dept_name FROM departments ORDER BY dept_name")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($departements as $dep):
                        ?>
                            <option value="<?= $dep['dept_no'] ?>"><?= htmlspecialchars($dep['dept_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="from_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" name="from_date" id="from_date" required>
                </div>

                <button type="submit" class="btn btn-success">Valider</button>
            </form>
        </div>

        <a href="javascript:history.back()" class="btn btn-secondary mt-3">← Retour</a>
    </div>
</div>
<script src="../assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
