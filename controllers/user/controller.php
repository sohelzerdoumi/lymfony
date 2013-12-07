<?php if (!defined('BASE_CMS') || BASE_CMS !== TRUE) exit;

class User_Page extends Controller_Page
{

	public function indexGET(){
		return $this->view('index' );
	}

	public function loginGET(){
		return $this->view('login' );
	}

    /**
     * @Parameter( name = 'login' , method = 'POST')
     * @Parameter( name = 'password' , method = 'POST')
     */
    public function loginPOST($login, $password){

    	$errors = array();
    	try{
    		$user = ServiceUtilisateur::login($login,$password);

    		$_SESSION['user'] = $user;
    		return redirect('');

    	}catch( LoginException $le){
    		$errors['login'] = $le->getMessage();
    	}catch( PasswordException $le){
    		$errors['password'] = $le->getMessage();
    	}

    	return $this->view('login' , array(
    		"error" => $errors 
    		));	
    }

	/**
	 * @Logged()
	 */
	public function logoutGET(){
		unset( $_SESSION['user'] );
		return redirect();
	}

	public function registrationGET(){
		return $this->view('registration' );
	}

    /**
     * @Parameter( name = 'login' , method = 'POST')
     * @Parameter( name = 'password' , method = 'POST')
     * @Parameter( name = 'password2' , method = 'POST')
     * @Parameter( name = 'firstname' , method = 'POST')
     * @Parameter( name = 'lastname' , method = 'POST')
     * @Parameter( name = 'email' , method = 'POST')
     */
    public function registrationPOST($login, $password , $password2, $firstname, $lastname, $email){

    	try{
    		$user = ServiceUtilisateur::registration($login,$password, $password2, $firstname, $lastname, $email);

    		if( is_object($user) ){
    			$_SESSION['user'] = $user;
    			return redirect('');
    		}
    	}catch( LoginException $le){
    		$errors['login'] = $le->getMessage();
    	}catch( PasswordException $pe){
    		$errors['password'] = $pe->getMessage();
    	}catch( Password2Exception $pe){
    		$errors['password2'] = $pe->getMessage();
    	}catch( EmailException $email){
    		$errors['email'] = $email->getMessage();
    	}

    	return $this->view('registration' , array(
    		"error" => $errors,

    		));
    }

	/**
	 * @Logged()
	 */
	public function deleteGET(){
		return $this->view('remove');
	}
	/**
	 * @Parameter( name = 'password' , method = 'POST')
	 * @Logged()
	 */
	public function deletePOST($password){
		if($_SESSION['user']->getPassword() == md5($password)){
			try{
				ServiceUtilisateur::removeUser($_SESSION['user']->getId());
				unset( $_SESSION['user'] );
				return $this->view('remove_success');
			}
			catch(UserNotFound $patate)
			{
				return $this->view('remove', array(
					"error" => 'Utilisateur Inconnu'
					));
			}
		}else{
			return $this->view('remove', array(
				"error" => 'Erreur mot de passe'
				));
		}
	}
	/**
	  * @Logged()
	  */
	public function editGET(){
		return $this->view('editUser', array(
			"user" => $_SESSION['user']
			));
	}
	/**
	 *  @Logged()
	 */
	public function editPOST(){
		$post = get_post('oldPassword', 'password', 'password2','lastname', 'firstname', 'email');
		try{
			ServiceUtilisateur::editUser($post);
			return $this->view('editUser',array(
				'success' => 'Profil modifié avec succès',
				"user" => $_SESSION['user']));
		}
		catch(EmailException $email){
			$errors['mail'] = $email->getMessage();
		}
		catch(PasswordException $password){
			$errors['password'] = $password->getMessage();
		}
		return $this->view('editUser' , array(
			"error" => $errors,
			"user" => $_SESSION['user']
			));

	}
	public function forgetGET(){
		return $this->view('lostPassword');
	}

	/**
	* @Parameter( name = 'email' , method = 'POST')
	*/
	public function forgetPOST($email){
		try{
			ServiceUtilisateur::processForgetPassword($email);
			return $this->view('lostPassword',array(
				'success' => 'Un nouveau mot de passe vous a été envoyé'));
		}
		catch(Email2Exception $e){
			return $this->view('lostPassword', array(
				'error' => 'Email Inconnu'
				));
		}

	}

}



