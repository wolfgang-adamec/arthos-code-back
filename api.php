<?php
// api.php - Minimalistischer API-Endpunkt für Arthos-Code
require_once __DIR__ . '/helpers.php';

arthos_log("--- Neuer API-Aufruf gestartet ---");

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); // Erlaubt Vite (lokal) den Zugriff während der Entwicklung
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET");

// 1. DATENBANK-VERBINDUNG
$db_host = "localhost";
$db_user = "wadamec";
$db_pass = "5pWporv47!t/uqx3"; // Bitte hier dein echtes Passwort eintragen
$db_name = "wadamec";

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "Datenbankverbindung fehlgeschlagen"]);
    exit;
}

// 2. REQUEST-VERARBEITUNG
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // JSON-Input aus dem Request-Body auslesen
    $inputRaw = file_get_contents('php://input');
    arthos_log("Raw Input empfangen", $inputRaw);

    $input = json_decode($inputRaw, true);
    arthos_log("Dekodiertes JSON", $input);

    $action = $input['action'];
    arthos_log("Routing zu Action: " . $action);    

    switch ($action) {
      case 'login':
        require_once 'actions/auth_actions.php';
        handleLogin($pdo, $input);
        break;
      case 'create_user':
        // Datei dediziert laden und Funktion ausführen
        require_once 'actions/user_actions.php';
        handleCreateUser($pdo, $input);
        break;
      case 'create_term':
        require_once 'actions/term_actions.php';
        handle_create_term ($pdo, $input);
      case 'get_users':
        // Dedizierte Datei für das Laden der Benutzerliste
        require_once 'actions/get_users.php';
        handleGetUsers($pdo); 
        break;
      case 'get_terms':
        require_once 'actions/get_terms.php';
        handle_get_terms ($pdo);
      default:
        arthos_log("Fehler: Unbekannte Action: " . $action);
        echo json_encode([
             'success' => false,
             'message' => 'Unbekannte Aktion im Router.'
             ]);
        exit;
        break;     
    }
}

// Falls jemand die API direkt per GET im Browser aufruft
if ($method === 'GET') {
    echo json_encode(["status" => "API bereit. Bitte POST-Request für Login nutzen."]);
    exit;
}

