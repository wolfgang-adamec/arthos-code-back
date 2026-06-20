<?php
/**
 * Arthos-Code - Backend-Aktionen: Begriffe auslesen
 */

function handle_get_terms ($pdo) 
{
  try {
    // Wir sortieren nach ID absteigend, damit neu angelegte Begriffe sofort oben in der Tabelle stehen
    $stmt  = $pdo->query("SELECT id_str, lang, value FROM terms ORDER BY id_str DESC");
    $terms = $stmt->fetchAll();

    // Wenn die Tabelle leer ist, wird ein leeres Array [] zurückgegeben
    echo json_encode ($terms);

  } catch (PDOException $e) {
    // Fehler intern protokollieren
    error_log ("Fehler bei get_terms: " . $e->getMessage());
        
    // Dem Frontend einen sauberen HTTP-Status oder ein leeres Array signalisieren
    echo json_encode([]);
  }
  exit;
}






