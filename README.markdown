## EasyTranslator-Plugin

#### *Warum ein Translator-Plugin und warum easy?*

Dieses Plugin stellt eine SQLite-Datenbank zur Verf�gung, in der Strings und ihre �bersetzung von beliebig vielen Sprachen gespeichert und abgerufen werden k�nnen. Bisher wird Stud.IP immer �ber [gettext](http://php.net/manual/de/book.gettext.php) �bersetzt bzw. lokalisiert. Das hat den Vorteil, dass gettext sehr schnell ist und unabh�ngig von der ohnehin schon �berlasteten MySQL-Datenbank arbeitet. Es hat den Nachteil, dass man die �bersetzungsdatei nicht on-the-fly �ndern kann und Plugins k�nnen sich fast gar nicht lokalisieren.

Das EasyTranslator-Plugin soll anderen Plugins helfen, sich lokalisieren zu lassen f�r beliebige Sprachen und Kontexte. Das soll einfacher funktionieren als gettext und trotzdem performant sein. Der EasyTranslator bietet eine GUI, um �bersetzungen anzufertigen und zu exportieren bzw. wieder importieren (ist noch in Arbeit) und es liefert die SQLite Datenbank gleich mit, die performant wil dateibasiert ist. Alternativ k�nnte man das Plugin �bertragen auf MongoDB, um das ganze nochmal schneller zu machen.

#### *�bersetzen mit EasyTranslator*

Bau ein Plugin, schreibe die Textstrings nicht wie bei gettext �blich mit der Unterstrichfunktion `_("Hallo Welt")`, sondern mit der l-Funktion `l("Hallo Welt")`.
Diese l-Funktion (l wie localize) k�mmert sich auch gleich um das Escapen �ber htmlReady. Falls das unerw�nscht sein sollte, gibt es als Alternative die ll-Funktion an. Das braucht man allerdings nur f�r Namen von Reitern und ein paar anderen Spezialf�llen.

Root und jeder andere, f�r den das Plugin freigeschaltet ist, kann unter Tools -> �bersetzung dann Strings f�r jede Sprache anlegen und die �bersetzung mit eingeben. Alle �nderungen sind sofort verf�gbar, der Webserver muss nat�rlich nicht wie bei gettext neu gestartet werden.

Es gibt auch die M�glichkeit, im zu �bersetzenden Plugin selbst die Strings zu bearbeiten. Alle mit der l-Funktion erstellten Strings k�nnen mit Strg-Klick editiert werden, ohne die Seite verlassen zu m�ssen. Auch hier werden alle �nderungen sofort sichtbar, nicht mal die Seite muss neu geladen werden.

#### *Was noch getan werden muss*

Das Plugin ist noch lange nicht fertig, um released zu werden. Es muss noch die M�glichkeit geben, �bersetzungen zu exportieren und sp�ter oder in einem anderen Stud.IP wieder zu importieren. Ein ideales Format w�re sicherlich eine PO-Datei, damit man auch gleich die Kernstrings mitpflegen k�nnte.
Die �bersetzungsseite braucht Filterm�glichkeiten, damit schnell nach Strings gesucht werden kann und beispielsweise alle String mit dem Wort "Einrichtung" ge�ndert werden kann.
Sprachen sollten hinzugef�gt oder wieder gel�scht werden k�nnen.
Root sollte f�r jede Sprache festlegen k�nnen, wer diese Sprache bearbeiten darf und wer nicht. Englisch und Deutsch k�nnten so fixe Sprachen sein, die von den Admins gepflegt werden und jeder Nutzer darf beliebige andere Sprachen hinzuf�gen wie Latein, Plattdeutsch oder die Pirrrratensprache.
Man sollte nach einzelnen Strings suchen k�nnen. Das Plugin muss dann ein grep in Stud.IP durchf�hren und schauen, wo wird der String verwendet, damit man als �bersetzer sich immer den Kontext vor Augen f�hren kann.
Andere Plugins brauchen eine Methode, um eine mitgelieferte �bersetzungsdatei (PO-Format) an EasyTranslator zu �bergeben, um gleich bei der Installation des Plugins die �bersetzungsdatei mit abzugeben.