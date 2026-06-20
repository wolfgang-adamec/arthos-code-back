<?php
/**
 * Arthos-Code - Backend-Aktionen für die Verwaltung von Begriffen.
 */

require_once 'functions.php';

function handle_create_term ($pdo, $input) 
{
    $new_id    = isset($input['id']) ? $input['id'] : '';
    $new_lang  = isset($input['lang']) ? $input['lang'] : '';
    $new_term  = isset($input['term']) ? $input['term'] : '';

    $new_id    = get_input_value ($input, "id");
    $new_lang  = get_input_value ($input; "lang");
    $new_term  = get_input_value ($input; "term");

    // Am Frontend wird sichergestellt, dass alle 3 Werte befuellt sind. Wenn sie nun leer sind, kann
    // das zum Beispiel bedeuten, dass die Werte von woanders geschickt werden.



    // Validierung (Sicherheitsnetz)
    if (empty($new_id) || empty($new_lang) || empty($new_term)) {
        echo json_encode([
            'success' => false,
            'message' => 'ID, Sprach-Code und Begriff dürfen nicht leer sein.'
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

        // In die Datenbank schreiben
        $insertStmt = $pdo->prepare("INSERT INTO terms (id_str, lang, value) VALUES (:id_str, :lang, :value)");
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

