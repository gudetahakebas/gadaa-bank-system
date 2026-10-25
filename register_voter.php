<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'officer') {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized.']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voterID = $_POST['voterID'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $age = $_POST['age'] ?? 0;
    $sex = $_POST['sex'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $shares = $_POST['shares'] ?? 0;
    $is_approved = ($_POST['status'] === 'yes') ? 'Approved' : 'Pending';
    
    // Auto-classification based on shares
    if ($shares >= 100000) {
        $category = 'Influential Shareholder';
    } else {
        $category = 'Non-Influential Shareholder';
    }

    if (empty($voterID) || empty($firstName) || empty($password)) {
        die(json_encode(['status' => 'error', 'message' => 'Required fields missing.']));
    }

    if (!isset($_FILES['shareDoc']) || $_FILES['shareDoc']['error'] !== UPLOAD_ERR_OK) {
        die(json_encode(['status' => 'error', 'message' => 'Please upload shareholder certificate/document.']));
    }
    
    $docExt = strtolower(pathinfo($_FILES['shareDoc']['name'], PATHINFO_EXTENSION));
    $allowedExts = ['pdf', 'jpg', 'jpeg', 'png'];
    if (!in_array($docExt, $allowedExts)) {
        die(json_encode(['status' => 'error', 'message' => 'Invalid document format. Allowed formats: PDF, JPG, JPEG, PNG.']));
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();

        $stmtU = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'voter')");
        $stmtU->execute([$voterID, $hashed_password]);
        $user_id = $pdo->lastInsertId();

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $photoName = '';
        $docName = '';

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photoName = $uploadDir . uniqid('voter_photo_') . '.' . $photoExt;
            move_uploaded_file($_FILES['photo']['tmp_name'], $photoName);
        }

        $docName = $uploadDir . uniqid('voter_doc_') . '.' . $docExt;
        move_uploaded_file($_FILES['shareDoc']['tmp_name'], $docName);

        $stmtV = $pdo->prepare("INSERT INTO voters (user_id, voter_id_str, voter_category, first_name, middle_name, last_name, age, sex, email, phone, share_value, share_doc_path, photo_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtV->execute([$user_id, $voterID, $category, $firstName, $middleName, $lastName, $age, $sex, $email, $phone, $shares, $docName, $photoName, $is_approved]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Voter registered successfully.']);
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
