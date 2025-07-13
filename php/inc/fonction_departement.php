<?php
include('connexion.php');

function getEmployesDuDepartement($pdo, $dept_no, $limit = 20, $offset = 0) {
    if (!$dept_no) return null;

    $stmt = $pdo->prepare("
        SELECT e.emp_no, e.first_name, e.last_name, t.title
        FROM dept_emp de
        JOIN employees e ON de.emp_no = e.emp_no
        JOIN titles t ON e.emp_no = t.emp_no AND t.to_date = '9999-01-01'
        WHERE de.dept_no = ? AND de.to_date = '9999-01-01'
        ORDER BY e.last_name, e.first_name
        LIMIT ? OFFSET ?
    ");

    $stmt->bindValue(1, $dept_no, PDO::PARAM_STR);
    $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countEmployesDuDepartement($pdo, $dept_no) {
    if (!$dept_no) return 0;

    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM dept_emp de
        WHERE de.dept_no = ? AND de.to_date = '9999-01-01'
    ");
    $stmt->execute([$dept_no]);
    return (int) $stmt->fetchColumn();
}

