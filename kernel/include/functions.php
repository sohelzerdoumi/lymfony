<?php if (!defined('BASE_CMS') || BASE_CMS !== TRUE) exit;

/**
 * Verifie si l'utilisateur est enregistré
 * @return  [boolean] 
 */
function check_user()
{
	return (isset($_SESSION['user']) && $_SESSION['user']);
}

/**
 * Récupère l'ip de l'utilisateur
 * 
 * @codeCoverageIgnore
 * @return [string] [visitor's ip]
 */
function get_ip(){
	return $_SERVER["REMOTE_ADDR"];
}

/**
 * @codeCoverageIgnore
 */
function get_langue()
{
	if( isset($_SERVER['HTTP_ACCEPT_LANGUAGE'] ) AND stripos($_SERVER['HTTP_ACCEPT_LANGUAGE'] , 'fr') == 0){
		return 'fr';
	}else{
		return 'en';
	}
}

/**
 * Récupère les données passées en POST
 *
 * @param   [string] [parameter name]
 *                        		.
 *                        		.
 * @param   [string] [parameter name]
 * 
 * @return [object] 
 */
function get_post()
{
	$post = new stdClass();
	foreach (func_get_args() as $var)
	{
		$post->$var = (isset($_POST[$var])) ? $_POST[$var] : NULL;
	}
	
	return $post;
}

/**
 * Check if it's POST request
 * 
 * return Boolean
 */
function method_post(){
	return  count($_POST) > 0; //$_SERVER['REQUEST_METHOD'] === 'POST';
}


/**
 * @codeCoverageIgnore
 */
function redirect($url = '')
{
	global $template;	

	if( defined('UNITTESTS') )
		return $url;

	header('Location: '.$template['root_path'].$url);
	exit;
}