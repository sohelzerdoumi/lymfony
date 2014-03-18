<?php if (!defined('BASE_CMS') || BASE_CMS !== TRUE) exit;

include __DIR__.'/../controller.php';

loadFiles(__DIR__ ."/");

 $uri = (isset($_GET['uri']) && $_GET['uri'] ) 
             ?  $_GET['uri']: $default_uri;



class Router{
	/**
	 * Chemin definie dans $_GET['uri']
	 * @var String
	 */
	private $uri;

	/**
	 * @var String 
	 * 		['GET','POST','PUT','DELETE']
	 */
	private $method;
	
	private $arguments;


	private $class_path;
	private $class_name;
	private $class_method;

	public function __construct( $uri = NULL){
		$this->uri = $uri;
		$this->load();
	}


	public static function isValidUri($uri){
		return preg_match("/^[a-zA-Z\\/]+$/", $uri);
	}


	public function load(){
		GLOBAL $default_uri;

		if( empty($this->uri) )
			$this->uri = $default_uri;

		if( Router::isValidUri($this->uri) == false)
		 	redirect();


		if( isset( $_POST['_method']))
		 	$this->method = $_POST['_method'];
		elseif( isset( $_SERVER['REQUEST_METHOD']) )
			$this->method = $_SERVER['REQUEST_METHOD'] ;
		else
			$this->method = 'GET';


		$uri = explode('/', $this->uri);

		if( count($uri) == 1){
			$uri[] = 'index';	
			$this->class_path =  $uri[0].'/';
			$this->class_name = ucfirst(strtolower($uri[0])).'_Page';		
			$this->class_method = 'index'. $this->method;		
		}else{
			$this->class_method = array_pop( $uri ). $this->method ;
			$names = array() ;
			foreach ($uri as $key => $value) {
				$names[] = ucfirst(strtolower($value));
			}
			$this->class_name =  implode('_', $names) .'_Page';
			$this->class_path = implode('/', $uri) . '/';
		}

	}

	public function treatAnnotations(){
			$this->arguments = array();
			try{
				$reflexion = new ReflectionAnnotatedMethod( $this->class_name, $this->class_method);


				foreach ( $reflexion->getAllAnnotations() as $annotation ){
					if( $annotation instanceof Parameter)
						$this->arguments[] = $annotation->value;
				}
			}catch( Exception $e){
				redirect();
			}
	}

	public function run(){
		GLOBAL $router;
		$router = $this;

		$this->load();
		
		if (file_exists( __DIR__.'/../../../controllers/'.$this->class_path.'/controller.php'))
		{
			require_once __DIR__.'/../../../controllers/'.$this->class_path.'/controller.php';
			$class_name=$this->class_name;
			$class_method=$this->class_method;

			$this->treatAnnotations();

			$controller = new $class_name();
			return call_user_func_array( array( $controller, $this->class_method), $this->arguments );	
		}	
	}

	/**
	 * Getter for method
	 *
	 * @return mixed
	 */
	public function getMethod()
	{
	    return $this->method;
	}
	
	/**
	 * Setter for method
	 *
	 * @param mixed $method Value to set
	 * @return self
	 */
	public function setMethod($method)
	{
	    $this->method = $method;
	    return $this;
	}
	

	/**
	 * Getter for uri
	 *
	 * @return mixed
	 */
	public function getUri()
	{
	    return $this->uri;
	}
	
	/**
	 * Setter for uri
	 *
	 * @param mixed $uri Value to set
	 * @return self
	 */
	public function setUri($uri)
	{
	    $this->uri = $uri;
	    return $this;
	}
	

	/**
	 * Getter for class_method
	 *
	 * @return mixed
	 */
	public function getClassMethod()
	{
	    return $this->class_method;
	}
	
	/**
	 * Setter for class_method
	 *
	 * @param mixed $classMethod Value to set
	 * @return self
	 */
	public function setClassMethod($classMethod)
	{
	    $this->class_method = $classMethod;
	    return $this;
	}
	

	/**
	 * Getter for class_name
	 *
	 * @return mixed
	 */
	public function getClassName()
	{
	    return $this->class_name;
	}
	
	/**
	 * Setter for class_name
	 *
	 * @param mixed $className Value to set
	 * @return self
	 */
	public function setClassName($className)
	{
	    $this->class_name = $className;
	    return $this;
	}
	

	/**
	 * Getter for class_path
	 *
	 * @return mixed
	 */
	public function getClassPath()
	{
	    return $this->class_path;
	}
	
	/**
	 * Setter for class_path
	 *
	 * @param mixed $classPath Value to set
	 * @return self
	 */
	public function setClassPath($classPath)
	{
	    $this->class_path = $classPath;
	    return $this;
	}
	

}