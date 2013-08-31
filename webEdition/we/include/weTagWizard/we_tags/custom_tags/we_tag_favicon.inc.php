<?php

$this->NeedsEndTag = false;
$this->Description = "Mit we:favicon werden Bilddateien (bestenfalls PNG32) in einem Favicon-Container (ICO) gespeichert.";

if(defined("FILE_TABLE")) { 
	$this->Attributes[] = new weTagData_selectorAttribute('src', FILE_TABLE, 'image/*', true, '');
	$this->Attributes[] = new weTagData_selectorAttribute('target', FILE_TABLE, 'application/*', true, ''); //image/x-icon ... Warum muss es "application/*"" sein?!
}

$this->Attributes[] = new weTagData_selectAttribute('watch', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false,'');
$this->Attributes[] = new weTagData_selectAttribute('forceUpdate', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false,''); 
$this->Attributes[] = new weTagData_selectAttribute('only', array(new weTagDataOption('href', false, '')), false, '');
