we_tag_favicon
==============

webEdition-tag for a favicon/shortcut icon


Beschreibung:

Das Tag <we:favicon src="" target="" /> erzeugt aus den angegebenen
Bild-Dokumenten (src) in der Zieldatei (target) ein Favicon-Container.

 * Ausgabe:
 * <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
 *
 * Attribute:
 * - src: IDs der Quellbilder, bestenfalls PNG32
 *       - Pflicht
 *       - Integer bzw. Integers getrennt durch Kommas
 *       - Beispiele:
 *           <we:favicon src="2" target="1" />
 *           <we:favicon src="2,3,4" target="1" />
 * - target: ID der Zieldatei (favicon.ico)
 *       - Pflicht
 *       - Integer
 *       - Beispiele:
 *           <we:favicon src="2" target="1" />
 *           <we:favicon src="2,3" target="1" />
 * - watch: Beobachtet den Zeitstempel der letzten Änderung
 *       - Optional
 *       - true/false (default: false)
 *       - Beispiele:
 *           <we:favicon src="2" target="1" watch="true" />
 * - forceUpdate: Erstellt das Favicon bei jedem Aufruf neu
 *       - Optional
 *       - true/false (default: false)
 *       - Beispiele:
 *           <we:favicon src="2" target="1" forceUpdate="true" />
 * - only: Liefert nur das href-Attribut zurück
 *       - Optional
 *       - href
 *       - Beispiele:
 *           <we:favicon src="2" target="1" only="href" />
 *


---------
Diese Software benutzt: https://github.com/chrisbliss18/php-ico
