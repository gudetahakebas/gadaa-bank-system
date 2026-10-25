<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'officer') {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized.']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candID = $_POST['candID'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $age = $_POST['age'] ?? 0;
    $sex = $_POST['sex'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $education = $_POST['education'] ?? '';
    $experience = $_POST['experience'] ?? 0;
    $bio = $_POST['bio'] ?? '';
    $category = $_POST['category'] ?? '';

    if (empty($candID) || empty($firstName) || empty($password) || empty($email) || empty($category)) {
        die(json_encode(['status' => 'error', 'message' => 'Required fields missing.']));
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();

        // 1. Create User
        $stmtU = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'candidate')");
        $stmtU->execute([$candID, $hashed_password]);
        $user_id = $pdo->lastInsertId();

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $photoName = '';
        $docName = '';

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photoName = $uploadDir . uniqid('cand_photo_') . '.' . $photoExt;
            move_uploaded_file($_FILES['photo']['tmp_name'], $photoName);
        }

        if (isset($_FILES['docs']) && $_FILES['docs']['error'] === UPLOAD_ERR_OK) {
            $docExt = pathinfo($_FILES['docs']['name'], PATHINFO_EXTENSION);
            $docName = $uploadDir . uniqid('cand_doc_') . '.' . $docExt;
            move_uploaded_file($_FILES['docs']['tmp_name'], $docName);
        }

        // 3. Create Candidate Profile
        $stmtC = $pdo->prepare("INSERT INTO candidates (user_id, candidate_id_str, candidate_category, first_name, middle_name, last_name, age, sex, email, phone, education, experience, bio, photo_path, doc_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtC->execute([$user_id, $candID, $category, $firstName, $middleName, $lastName, $age, $sex, $email, $phone, $education, $experience, $bio, $photoName, $docName]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Candidate registered successfully.']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo json_encode(['status' => 'error', 'message' => 'Username already exists. Please choose another username.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
?>
