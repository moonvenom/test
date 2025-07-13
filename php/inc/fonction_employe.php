<?php
include('connexion.php');

function getEmployeDetails(PDO $pdo, int $emp_no): ?array {
    $sql = "
        SELECT 
            e.emp_no,
            e.first_name,
            e.last_name,
            e.gender,
            e.birth_date,
            e.hire_date,
            t.title,
            s.salary,
            d.dept_name
        FROM employees e
        LEFT JOIN titles t 
            ON t.emp_no = e.emp_no AND t.to_date IS NULL
        LEFT JOIN salaries s 
            ON s.emp_no = e.emp_no AND s.to_date = (
                SELECT MAX(s2.to_date) FROM salaries s2 WHERE s2.emp_no = e.emp_no
            )
        LEFT JOIN dept_emp de 
            ON de.emp_no = e.emp_no AND de.to_date = (
                SELECT MAX(de2.to_date) FROM dept_emp de2 WHERE de2.emp_no = e.emp_no
            )
        LEFT JOIN departments d 
            ON d.dept_no = de.dept_no
        WHERE e.emp_no = :emp_no
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['emp_no' => $emp_no]);
    $emp = $stmt->fetch(PDO::FETCH_ASSOC);
    return $emp ?: null;
}

function getHistoriqueTitres(PDO $pdo, int $emp_no): array {
    $sql = "
        SELECT title, from_date, to_date
        FROM titles
        WHERE emp_no = :emp_no
        ORDER BY from_date DESC
        LIMIT 20
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['emp_no' => $emp_no]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHistoriqueSalaires(PDO $pdo, int $emp_no): array {
    $sql = "
        SELECT salary, from_date, to_date
        FROM salaries
        WHERE emp_no = :emp_no
        ORDER BY from_date DESC
        LIMIT 20
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['emp_no' => $emp_no]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEmploiLePlusLong(PDO $pdo, int $emp_no): ?array {
    $sql = "
        SELECT title, from_date, to_date,
               DATEDIFF(COALESCE(to_date, CURDATE()), from_date) AS duree
        FROM titles
        WHERE emp_no = :emp_no
        ORDER BY duree DESC
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['emp_no' => $emp_no]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

function getAffectationActuelle(PDO $pdo, int $emp_no): ?array {
    $sql = "
        SELECT d.dept_no, d.dept_name, de.from_date
        FROM dept_emp de
        JOIN departments d ON d.dept_no = de.dept_no
        WHERE de.emp_no = :emp_no AND de.to_date = '9999-01-01'
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['emp_no' => $emp_no]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
