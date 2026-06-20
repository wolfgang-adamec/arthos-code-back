/**
 * Anwendungscode (Einstiegspunkt)
 */

// Das Hello World sauber in einer Funktion verpackt

function start_application () 
{
    // Hier nutzen wir die getElement-Funktion aus dem Arthos-Framework
    const meinElement = getElement('gruss-box');
 
    if (meinElement) {
       meinElement.textContent = 'Hello World aus dem Arthos-Framework!';
    }
}

// Die Funktion wird ausgeführt, sobald die Datei geladen ist
start_application();

