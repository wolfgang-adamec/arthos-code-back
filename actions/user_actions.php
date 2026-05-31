<?php
/**
 * Arthos-Code - Backend-Aktionen für Benutzerverwaltung
 */

function handleCreateUser($pdo, $input) {
    $newUsername = isset($input['username']) ? trim($input['username']) : '';
    $newPassword = isset($input['password']) ? $input['password'] : '';

    // Validierung (Sicherheitsnetz)
    if (empty($newUsername) || empty($newPassword)) {
        echo json_encode([
            'success' => false,
            'message' => 'Benutzername und Passwort dürfen nicht leer sein.'
        ]);
        exit;
    }

    try {
        // Duplikat-Schutz
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
        $checkStmt->execute([':username' => $newUsername]);
        
        if ($checkStmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'Dieser Benutzername ist bereits vergeben.'
            ]);
            exit;
        }

        // Sicheres Hashing (Bcrypt)
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // In die Datenbank schreiben
        $insertStmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
        $result = $insertStmt->execute([
            ':username' => $newUsername,
            ':password_hash' => $passwordHash
        ]);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Benutzer erfolgreich angelegt.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Datenbankfehler beim Einfügen des Datensatzes.'
            ]);
        }

    } catch (PDOException $e) {
        error_log("Fehler bei create_user: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Ein interner Serverfehler ist aufgetreten.'
        ]);
    }
    exit;
}

