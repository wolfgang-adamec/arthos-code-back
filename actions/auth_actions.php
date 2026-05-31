<?php
/**
 * Arthos-Code - Backend-Aktionen für Authentifizierung
 */

function handleLogin($pdo, $input) {
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    arthos_log("auth_actions.php geladen. Verarbeite Login für User: " . ($input['username'] ?? 'UNBEKANNT'));

    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "error" => "Felder unvollständig"]);
        exit;
    }

    try {
        // Benutzer in der DB suchen
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
          arthos_log("Login-Fehlversuch: Benutzer '$username' nicht in Datenbasis gefunden.");
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => 'Ungültige Anmeldedaten']);
          exit;
        }

        arthos_log("Benutzer gefunden. Starte password_verify(). Geladener Hash: " . $user['password_hash']);

        // Passwort prüfen (Vergleich des Plaintext-Passworts mit dem Bcrypt-Hash aus der DB)
        if ($user && password_verify($password, $user['password_hash'])) {

            // Login erfolgreich -> Sofort die Projektdaten synchron mitliefern
            // (Nutzt p.title und p.description passend zu deiner DB-Struktur)
            $projectStmt = $pdo->query("SELECT id, title, description, status FROM projects");
            $projects = $projectStmt->fetchAll();

            echo json_encode([
                "success" => true,
                "message" => "Login erfolgreich",
                "user" => ["username" => $user['username']],
                "projects" => $projects
            ]);
        } else {
            echo json_encode(["success" => false, "error" => "Ungültige Anmeldedaten"]);
        }
        
    } catch (PDOException $e) {
        error_log("Fehler bei login: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => 'Ein interner Serverfehler ist aufgetreten.'
        ]);
    }
    exit;
}

