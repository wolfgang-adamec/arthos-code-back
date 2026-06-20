<?php
// Daten definieren
$data = [
    '{{public_id}}' => '1.1',
    '{{first_name}}' => 'Giovanni',
    '{{last_name}}' => 'Falcone',
    '{{domain}}' => 'Legal',
    '{{date_of_birth}}' => '18. Mai 1939',
    '{{date_of_death}}' => '23. Mai 1992',
    '{{mother_first_name}}' => 'Luisa',
    '{{mother_last_name}}' => 'Falcone',
    '{{father_first_name}}' => 'Arturo',
    '{{father_last_name}}' => 'Falcone',
    '{{core_achievement}}' => 'Kampf gegen die Mafia.',
    '{{evidence}}' => '<strong>Maxiprocesso di Palermo (1986–1992):</strong> Der Mammut-Prozess, bei dem Falcone als führender Ermittlungsrichter der Anklage hunderte Mitglieder der Cosa Nostra hinter Gitter brachte und damit die Existenz der kriminellen Organisation gerichtlich unumstößlich bewies.'
];

// 1. Die HTML-Vorlage als reinen Text vom Linux-Server einlesen
$html_template = file_get_contents ('person-template.html');

// 2. Platzhalter durch echte Daten ersetzen
$output = strtr ($html_template, $data);

// 3. Das fertige Ergebnis an den Browser senden
echo $output;
?>

