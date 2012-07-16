## EasyTranslator-Plugin

#### *Warum ein Translator-Plugin und warum easy?*

Dieses Plugin stellt eine SQLite-Datenbank zur Verf�gung, in der Strings und ihre �bersetzung von beliebig vielen Sprachen gespeichert und abgerufen werden k�nnen. Bisher wird Stud.IP immer �ber [gettext](http://php.net/manual/de/book.gettext.php) �bersetzt bzw. lokalisiert. Das hat den Vorteil, dass gettext sehr schnell ist und unabh�ngig von der ohnehin schon �berlasteten MySQL-Datenbank arbeitet. Es hat den Nachteil, dass man die �bersetzungsdatei nicht on-the-fly �ndern kann und Plugins k�nnen sich fast gar nicht lokalisieren.

Das EasyTranslator-Plugin ist f�r Plugins gebaut worden. Ein Plugin, das eine eigene PO-Datei f�r jede Sprache mit sich bringt (beispielsweise im Unterordner `locale/`, kann mit folgenden Zeilen die PO-Datei in die Datenbank des EasyTranslator einbauen:

    if (class_exists("EasyTranslator")) {
        PluginEngine::getPlugin("EasyTranslator")->add_po_file(
            dirname(__file__)."/locale/de.po",
            "de_DE",
            "my_plugin"
        );
    }

#### *�bersetzen mit EasyTranslator*

Bau ein Plugin, schreibe die Textstrings nicht wie bei gettext �blich mit der Unterstrichfunktion `_("Hallo Welt")`, sondern mit der l-Funktion `l("Hallo Welt")`.
Diese l-Funktion (l wie localize) k�mmert sich auch gleich um das Escapen �ber htmlReady. Falls das unerw�nscht sein sollte, gibt es als Alternative die ll-Funktion an. Das braucht man allerdings nur f�r Namen von Reitern und ein paar anderen Spezialf�llen.

Root und jeder andere, f�r den das Plugin freigeschaltet ist, kann unter Tools -> �bersetzung dann Strings f�r jede Sprache anlegen und die �bersetzung mit eingeben. Alle �nderungen sind sofort verf�gbar, der Webserver muss nat�rlich nicht wie bei gettext neu gestartet werden.

Es gibt auch die M�glichkeit, im zu �bersetzenden Plugin selbst die Strings zu bearbeiten. Alle mit der l-Funktion erstellten Strings k�nnen mit Strg-Klick editiert werden, ohne die Seite verlassen zu m�ssen. Auch hier werden alle �nderungen sofort sichtbar, nicht mal die Seite muss neu geladen werden.

#### *Exportieren von �bersetzungen*

Wenn man einmal eine �bersetzung seines Plugins angelegt hat, m�chte man die auch weiter verwenden. EasyTranslator bietet die M�glichkeit, die �bersetzungen nach Sprache und optional nach origin als PO-Datei zu exportieren. Das ist praktisch, denn der EasyTranslator kann eben diese PO-Dateien auch wieder importieren (wobei da auch wieder ein Origin angegeben werden kann).

#### *Was noch getan werden muss*

* Sprachen sollten hinzugef�gt oder wieder gel�scht werden k�nnen.
* Root sollte f�r jede Sprache festlegen k�nnen, wer diese Sprache bearbeiten darf und wer nicht. Englisch und Deutsch k�nnten so fixe Sprachen sein, die von den Admins gepflegt werden und jeder Nutzer darf beliebige andere Sprachen hinzuf�gen wie Latein, Plattdeutsch oder die Pirrrratensprache.
* Man sollte nach einzelnen Strings suchen k�nnen. Das Plugin muss dann ein grep in Stud.IP durchf�hren und schauen, wo wird der String verwendet, damit man als �bersetzer sich immer den Kontext vor Augen f�hren kann.

