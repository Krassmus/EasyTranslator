## EasyTranslator-Plugin

#### *Warum ein Translator-Plugin und warum easy?*

Dieses Plugin stellt eine SQLite-Datenbank zur Verfügung, in der Strings und ihre Übersetzung von beliebig vielen Sprachen gespeichert und abgerufen werden können. Bisher wird Stud.IP immer über [gettext](http://php.net/manual/de/book.gettext.php) übersetzt bzw. lokalisiert. Das hat den Vorteil, dass gettext sehr schnell ist und unabhängig von der ohnehin schon überlasteten MySQL-Datenbank arbeitet. Es hat den Nachteil, dass man die Übersetzungsdatei nicht on-the-fly ändern kann und Plugins können sich fast gar nicht lokalisieren.

Das EasyTranslator-Plugin ist für Plugins gebaut worden. Ein Plugin, das eine eigene PO-Datei für jede Sprache mit sich bringt (beispielsweise im Unterordner `locale/`, kann mit folgenden Zeilen die PO-Datei in die Datenbank des EasyTranslator einbauen:

    if (class_exists("EasyTranslator")) {
        PluginEngine::getPlugin("EasyTranslator")->add_po_file(
            dirname(__file__)."/locale/de.po",
            "de_DE",
            "my_plugin"
        );
    }

#### *Übersetzen mit EasyTranslator*

Bau ein Plugin, schreibe die Textstrings nicht wie bei gettext üblich mit der Unterstrichfunktion `_("Hallo Welt")`, sondern mit der l-Funktion `l("Hallo Welt")`.
Diese l-Funktion (l wie localize) kümmert sich auch gleich um das Escapen über htmlReady. Falls das unerwünscht sein sollte, gibt es als Alternative die ll-Funktion an. Das braucht man allerdings nur für Namen von Reitern und ein paar anderen Spezialfällen.

Root und jeder andere, für den das Plugin freigeschaltet ist, kann unter Tools -> Übersetzung dann Strings für jede Sprache anlegen und die Übersetzung mit eingeben. Alle Änderungen sind sofort verfügbar, der Webserver muss natürlich nicht wie bei gettext neu gestartet werden.

Es gibt auch die Möglichkeit, im zu übersetzenden Plugin selbst die Strings zu bearbeiten. Alle mit der l-Funktion erstellten Strings können mit Strg-Klick editiert werden, ohne die Seite verlassen zu müssen. Auch hier werden alle Änderungen sofort sichtbar, nicht mal die Seite muss neu geladen werden.

#### *Exportieren von Übersetzungen*

Wenn man einmal eine Übersetzung seines Plugins angelegt hat, möchte man die auch weiter verwenden. EasyTranslator bietet die Möglichkeit, die Übersetzungen nach Sprache und optional nach origin als PO-Datei zu exportieren. Das ist praktisch, denn der EasyTranslator kann eben diese PO-Dateien auch wieder importieren (wobei da auch wieder ein Origin angegeben werden kann).

#### *Was noch getan werden muss*

* Sprachen sollten hinzugefügt oder wieder gelöscht werden können.
* Root sollte für jede Sprache festlegen können, wer diese Sprache bearbeiten darf und wer nicht. Englisch und Deutsch könnten so fixe Sprachen sein, die von den Admins gepflegt werden und jeder Nutzer darf beliebige andere Sprachen hinzufügen wie Latein, Plattdeutsch oder die Pirrrratensprache.
* Man sollte nach einzelnen Strings suchen können. Das Plugin muss dann ein grep in Stud.IP durchführen und schauen, wo wird der String verwendet, damit man als Übersetzer sich immer den Kontext vor Augen führen kann.

