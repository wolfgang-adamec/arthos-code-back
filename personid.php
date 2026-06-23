<?php
  require_once 'helper_fun.php';

  log_message ("-----------------------", "info");
  log_message ("Neuer Aufruf.",           "info");

  $xml_obj = parse_xml ("falcone.xml");
  
  $dom->load ('falcone.xml');
  if (!$dom) {
    die ("Fehler beim Laden der XML-Datei.");
  }
  $placeholder_data = [];

  // Das Haupt-Element <person> wird geholt.
  $person = $dom->getElementsByTagName('person')->item(0);

  log_message ("nach person", "info");

  if ($person) {
    // Wir gehen Schritt für Schritt durch jedes Kind-Element (Tags im Inneren)
    foreach ($person->childNodes as $node) {
      // Sicherstellen, dass es sich um ein echtes XML-Element handelt (kein Zeilenumbruch/Text)
      if ($node->nodeType === XML_ELEMENT_NODE) {
        // Tag-Name (z.B. "first_name") zu Platzhalter machen (z.B. "{{FIRST_NAME}}")
        $placeholder = '{{' . $node->nodeName . '}}';         
        // Den Inhalt des Tags auslesen
        $placeholder_data[$placeholder] = htmlspecialchars($node->nodeValue);
      }
    }
  }

  // 1. Die HTML-Vorlage reinen Text vom Linux-Server einlesen
  $html_template = file_get_contents ('person-template.html');

  // 2. Platzhalter durch echte Daten ersetzen
  $output = strtr ($html_template, $placeholder_data);

  // 3. Das fertige Ergebnis an den Browser senden
  echo $output;
?>

