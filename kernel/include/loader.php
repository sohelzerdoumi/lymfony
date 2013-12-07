<?php if (!defined('BASE_CMS') || BASE_CMS !== TRUE) exit;

/*
 *  Charge les entités dans le repertoire 'entity'
 */



function loadFiles($dossier){
    $ouverture=opendir($dossier);
    while( $filename=readdir($ouverture) ) {
        if ($filename == '.' || $filename == '..') continue;

        if (is_dir($dossier."/".$filename)) {
            loadFiles($dossier.$filename.'/');
        }else{
            require_once $dossier.$filename;
        }
    }
}

loadFiles(__DIR__.'/../service/');
