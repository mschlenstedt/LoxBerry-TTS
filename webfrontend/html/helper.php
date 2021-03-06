<?php

/**
* Submodul: Helper
*
**/


/**
* Function : recursive_array_search --> durchsucht eine Array nach einem Wert und gibt 
* den dazugehörigen key zurück
* @param: 	$needle = Wert der gesucht werden soll
*			$haystack = Array die durchsucht werden soll
*
* @return: $key
**/

function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}



/**
* Function : array_multi_search --> search threw a multidimensionales array for a specific value
* Optional you can search more detailed on a specific key'
* https://sklueh.de/2012/11/mit-php-ein-mehrdimensionales-array-durchsuchen/
*
* @return: array with result
**/

 function array_multi_search($mSearch, $aArray, $sKey = "")
{
    $aResult = array();
    foreach( (array) $aArray as $aValues) {
        if($sKey === "" && in_array($mSearch, $aValues)) $aResult[] = $aValues;
        else 
        if(isset($aValues[$sKey]) && $aValues[$sKey] == $mSearch) $aResult[] = $aValues;
    }
    return $aResult;
}



/**
* Function : File_Put_Array_As_JSON --> erstellt eine JSON Datei aus einer Array
*
* @param: 	Dateiname
*			Array die gespeichert werden soll			
* @return: Datei
**/	

function File_Put_Array_As_JSON($FileName, $ar, $zip=false) {
	if (! $zip) {
		return file_put_contents($FileName, json_encode($ar));
    } else {
		return file_put_contents($FileName, gzcompress(json_encode($ar)));
    }
}

/**
* Function : File_Get_Array_From_JSON --> liest eine JSON Datei ein und erstellt eine Array
*
* @param: 	Dateiname
* @return: Array
**/	

function File_Get_Array_From_JSON($FileName, $zip=false) {
	// liest eine JSON Datei und erstellt eine Array
    if (! is_file($FileName)) 	{ LOGGING("The file $FileName does not exist.", 3); exit; }
		if (! is_readable($FileName))	{ LOGGING("The file $FileName could not be loaded.", 3); exit;}
            if (! $zip) {
				return json_decode(file_get_contents($FileName), true);
            } else {
				return json_decode(gzuncompress(file_get_contents($FileName)), true);
	    }
}



/**
* Function : select_t2s_engine --> selects the configured t2s engine for speech creation
*
* @param: empty
* @return: 
**/

function select_t2s_engine()  {
	global $config;
	
	if ($config['TTS']['t2s_engine'] == 1001) {
		include_once("voice_engines/VoiceRSS.php");
	}
	if ($config['TTS']['t2s_engine'] == 3001) {
		include_once("voice_engines/MAC_OSX.php");
	}
	if ($config['TTS']['t2s_engine'] == 6001) {
		include_once("voice_engines/ResponsiveVoice.php");
	}
	if ($config['TTS']['t2s_engine'] == 7001) {
		include_once("voice_engines/Google.php");
	}
	if ($config['TTS']['t2s_engine'] == 5001) {
		include_once("voice_engines/Pico_tts.php");
	}
	if ($config['TTS']['t2s_engine'] == 4001) {
		include_once("voice_engines/Polly.php");
	}
}



/**
* Function : load_t2s_text --> check if translation file exit and load into array
*
* @param: 
* @return: array 
**/

function load_t2s_text(){
	global $config, $t2s_langfile, $t2s_text_stand, $templatepath;
	
	if (file_exists($templatepath.'/lang/'.$t2s_langfile)) {
		$TL = parse_ini_file($templatepath.'/lang/'.$t2s_langfile, true);
	} else {
		LOGGING("For selected T2S language no translation file still exist! Please go to LoxBerry Plugin translation and create a file for selected language ".substr($config['TTS']['messageLang'],0,2),3);
		exit;
	}
	return $TL;
}



/**
* Function : mp3_files --> check if playgong mp3 file is valid in ../tts/mp3/
*
* @param: 
* @return: array 
**/

function mp3_files($playgongfile) {
	global $MessageStorepath;
	
	$scanned_directory = array_diff(scandir($MessageStorepath.'/mp3/', SCANDIR_SORT_DESCENDING), array('..', '.'));
	$file_only = array();
	foreach ($scanned_directory as $file) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if ($extension == 'mp3') {
			array_push($file_only, $file);
		}
	}
	#print_r($file_only);
	return (in_array($playgongfile, $file_only));
}

 
 
?>
