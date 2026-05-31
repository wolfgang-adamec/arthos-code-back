<?php
/**
 * Arthos-Code - Backend-Aktionen: Benutzerliste auslesen
 */

function handleGetUsers($pdo) {
    try {
        // Wir sortieren nach ID absteigend, damit neu angelegte User sofort oben in der Tabelle stehen
        $stmt = $pdo->query("SELECT id, username FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll();

        // Wenn die Tabelle leer ist, wird ein leeres Array [] zurückgegeben
        echo json_encode($users);

    } catch (PDOException $e) {
        // Fehler intern protokollieren
        error_log("Fehler bei get_users: " . $e->getMessage());
        
        // Dem Frontend einen sauberen HTTP-Status oder ein leeres Array signalisieren
        echo json_encode([]);
    }
    exit;
}






