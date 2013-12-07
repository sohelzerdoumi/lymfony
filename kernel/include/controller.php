<?php if (!defined('BASE_CMS') || BASE_CMS !== TRUE) exit;

//include_once(__DIR__.'/lib/Twig/Autoloader.php');
include_once(__DIR__.'/lib/addendum/annotations.php');

abstract class Controller_Page
{
	protected $base_data;
	protected $db;
	protected $user;
	protected $host;
	
	private   $twig;
	
	public function __construct()
	{
		global $template, $config, $uri , $lang, $router, $em;
		$this->base_data      = $template;	
		$this->router      		= $router;	
		$this->em             =& $em;
		$this->uri            =& $uri;
		$this->data       	  = array();

		Twig_Autoloader::register();
	    $loader = new Twig_Loader_Filesystem( array( __DIR__.'/../../controllers/' , __DIR__.'/../../view/template/')  ); // Dossier contenant les templates
		$this->twig 		  =  new Twig_Environment($loader, array('cache' => false));
		$this->twig->addGlobal('post', isset($_POST) ? $_POST : NULL );
		$this->twig->addGlobal('get', isset($_GET) ? $_GET : NULL );
	}
	
	protected function view($file, $data = array(), $return = FALSE)
	{

		$this->twig->addGlobal('template', $this->base_data );
		$this->twig->addGlobal('user', ( isset($_SESSION['user']) ? $_SESSION['user'] : NULL) );

		if( defined('UNITTESTS') ){
			$data['template_name'] = $file;
			return $data;
		}else{
			$template = $this->twig->loadTemplate( $this->router->getClassPath().'/views/'.$file.'.twig');
			echo $template->render(array(
				'data'		  => $data ,
			) ); 			
		}

	}
}