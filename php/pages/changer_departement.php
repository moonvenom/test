<?php
include('../inc/connexion.php');

$emp_no = $_POST['emp_no'] ?? null;
$dept_no = $_POST['dept_no'] ?? null;
$from_date = $_POST['from_date'] ?? null;

if (!$emp_no || !$dept_no || !$from_date) {
    echo "Données manquantes.";
    exit;
}

// Vérification que la nouvelle date n'est pas antérieure à l'affectation actuelle
$sql = "SELECT from_date FROM dept_emp WHERE emp_no = :emp_no AND to_date = '9999-01-01'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['emp_no' => $emp_no]);
$current = $stmt->fetch();

if ($current && $from_date < $current['from_date']) {
    header("Location: fiche_employe.php?emp_no=" . urlencode($emp_no) . "&error=date");
    exit;
}

try {
    $pdo->beginTransaction();

    // Fermer l'ancien département
    $sql1 = "UPDATE dept_emp 
             SET to_date = DATE_SUB(:from_date, INTERVAL 1 DAY)
             WHERE emp_no = :emp_no AND to_date > :from_date";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([
        'from_date' => $from_date,
        'emp_no' => $emp_no
    ]);

    // Insérer la nouvelle affectation
    $sql2 = "INSERT INTO dept_emp (emp_no, dept_no, from_date, to_date)
             VALUES (:emp_no, :dept_no, :from_date, '9999-01-01')";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        'emp_no' => $emp_no,
        'dept_no' => $dept_no,
        'from_date' => $from_date
    ]);

    $pdo->commit();
    header("Location: fiche_employe.php?emp_no=" . urlencode($emp_no) . "&modif=ok");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erreur lors du changement de département : " . $e->getMessage();
}
