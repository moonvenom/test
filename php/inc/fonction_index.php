<?php
include('connexion.php');

function getDepartements($pdo, $filters = []) {
    $conditions = [];
    $params = [];

    $sql = "
        SELECT 
            d.dept_no, 
            d.dept_name, 
            CONCAT(e.first_name, ' ', e.last_name) AS manager,
            COUNT(de.emp_no) AS nb_employes
        FROM departments d
        JOIN dept_manager dm ON d.dept_no = dm.dept_no
        JOIN employees e ON dm.emp_no = e.emp_no
        LEFT JOIN dept_emp de ON d.dept_no = de.dept_no AND de.to_date = '9999-01-01'
        WHERE dm.to_date = '9999-01-01'
    ";

    if (!empty($filters['dept_name'])) {
        $conditions[] = "d.dept_name LIKE :dept_name";
        $params['dept_name'] = '%' . $filters['dept_name'] . '%';
    }

    if (!empty($filters['emp_name'])) {
        $conditions[] = "(e.first_name LIKE :emp_name OR e.last_name LIKE :emp_name)";
        $params['emp_name'] = '%' . $filters['emp_name'] . '%';
    }

    if (!empty($filters['age_min'])) {
        $conditions[] = "TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) >= :age_min";
        $params['age_min'] = $filters['age_min'];
    }

    if (!empty($filters['age_max'])) {
        $conditions[] = "TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) <= :age_max";
        $params['age_max'] = $filters['age_max'];
    }

    if ($conditions) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    // Comme on utilise COUNT, on doit grouper par les colonnes non agrégées
    $sql .= " GROUP BY d.dept_no, d.dept_name, manager";
    $sql .= " ORDER BY d.dept_name LIMIT 20";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getStatistiquesEmplois($pdo) {
    $sql = "
        SELECT 
            t.title,
            SUM(CASE WHEN e.gender = 'M' THEN 1 ELSE 0 END) AS nb_hommes,
            SUM(CASE WHEN e.gender = 'F' THEN 1 ELSE 0 END) AS nb_femmes,
            ROUND(AVG(s.salary), 2) AS salaire_moyen
        FROM titles t
        JOIN employees e ON t.emp_no = e.emp_no
        JOIN salaries s ON e.emp_no = s.emp_no
        WHERE t.to_date = '9999-01-01' AND s.to_date = '9999-01-01'
        GROUP BY t.title
        ORDER BY t.title
    ";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


