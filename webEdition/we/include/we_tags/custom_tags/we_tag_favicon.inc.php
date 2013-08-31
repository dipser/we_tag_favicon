<?php

/**
 * we-Tag we:favicon
 *
 * File: we_tag_favicon.inc.php
 * Path: webEdition/we/include/we_tags/custom_tags/
 *
 * @author Aurelian Hermand (aurel@hermand.de)
 * @version 1.0.0
 * @date 31.08.2013
 *
 *
 *
 * Beschreibung:
 *
 * Das Tag <we:favicon src="" target="" /> erzeugt aus den angegebenen
 * Bild-Dokumenten (src) in der Zieldatei (target) ein Favicon-Container.
 * 
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
 *
 * ---------
 * Diese Software benutzt: https://github.com/chrisbliss18/php-ico
 */

function we_tag_favicon($attribs, $content) {

	// Fehlende Pflicht-Attribute abfangen
	if ($missingAttrib = attributFehltError($attribs, "src", __FUNCTION__)) {
		return $missingAttrib;
	}
	if ($missingAttrib = attributFehltError($attribs, "target", __FUNCTION__)) {
		return $missingAttrib;
	}

	// Attribute auslesen
	/*
 	* get an attribute from $attribs, and return its value according to default
 	* @param string $name attributes name
 	* @param array $attribs array containg the attributes
 	* @param mixed $default default value
 	* @param bool $isFlag determines if this is a flag (true/false -value)
 	* @param bool $useGlobal check if attribute value is a php-variable and is found in $GLOBALS
 	* @return mixed returns the attributes value or default if not set
 	*/
	$srcs = explode(',', we_getTagAttribute("src", $attribs));
	$target = we_getTagAttribute("target", $attribs);
	$watch = weTag_getAttribute("watch", $attribs, false, true); // default: false
	$forceUpdate = weTag_getAttribute("forceUpdate", $attribs, false, true); // default: false
	$only = weTag_getAttribute("only", $attribs); // Possibility: href


	// Überprüfen ob Zeitstempel der Quelldateien neuer sind => Update erzwingen
	if ( $watch ) {

		$faviconObject = new we_textDocument();
		$faviconObject->initByID($target);
		$faviconTime = $faviconObject->Published;

		$highestSrcTime = $faviconTime;
		for ($i = 0; $i < count($srcs); $i++) {

			$src = intval($srcs[$i]);

			if ($src > 0) {
				$srcObject[$src] = new we_textDocument();
				$srcObject[$src]->initByID($src);
			}

			$srcTime = $srcObject[$src]->Published;
			$highestSrcTime = $highestSrcTime > $srcTime ? $highestSrcTime : $srcTime;
		}

		if ($faviconTime < $highestSrcTime) {
			$forceUpdate = true;
		}
	}
	
	// Update ausführen
	if ( $forceUpdate ) {

		require_once('class-php-ico.php');
		
		$ico_lib = new PHP_ICO();

		for ($i = 0; $i < count($srcs); $i++) {

			$src = intval($srcs[$i]);

			if($src > 0) {

				if ( !isset($srcObject[$src]) ) {
					$srcObject[$src] = new we_textDocument();
					$srcObject[$src]->initByID($src);
				}

				$ico_lib->add_image( $_SERVER['DOCUMENT_ROOT'].$srcObject[$src]->Path );
			}
		}

		$result = $ico_lib->_get_ico_data();

		try {
			// Sichern in $target
			if ( !isset($faviconObj) ) {
				$faviconObject = new we_textDocument();
				$faviconObject->initByID($target);
			}
			$faviconObject->setElement('data', $result);
			$faviconObject->we_save();
		} catch (exception $e) {
			echo "fatal error: " . $e->getMessage();
		}
	}


	// Favicon-Pfad
	$faviconPath = '';
	if ( isset($faviconObject) ) { // Favicon-Pfad aus dem bestehenden Object übernehmen
		$faviconPath = $faviconObject->Path;
	} else { // Favicon-Pfad ermitteln
		$row = getHash('SELECT Path,IsFolder,IsDynamic FROM ' . FILE_TABLE . ' WHERE ID=' . intval($target), $GLOBALS['DB_WE']);
		if (!empty($row)) {
			$url = $row['Path'] . ($row['IsFolder'] ? '/' : '');
			$faviconPath = $url;
		}
	}

	$srcObject = null;
	$faviconObject = null;


	// Ausgabe
	if ($only == 'href') {
		return $faviconPath;
	} else {
		return '<link rel="shortcut icon" type="image/x-icon" href="'.$faviconPath.'" />'."\n";
	}


}

