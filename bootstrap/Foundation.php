<?php

class Foundation
{
	protected $env;

	public function __construct(Environment $env)
	{
		$this->env = $env;
	}

	public function run()
	{
		$envName = $this->env->environment();

		//Read the configuration
    	$config = new Phalcon\Config\Adapter\Ini(__DIR__.'/../app/config/'.$envName.'/main.ini');

		try {

		    //Register an autoloader
		    $loader = new \Phalcon\Loader();

		    $loader->registerDirs(array(
		        $config->application->controllersDir,
		        $config->application->modelsDir,
		        $config->application->librariesDir
		    ))->register();

		    //Create a DI
		    $di = new Phalcon\DI\FactoryDefault();

		    /**
		     * Environment injection
		     */
		    $env = $this->env;

		    $di->set('env', function() use ($env) {

		    	return $env;
		    });

		    /**
		    * add routing capabilities
		    */
		    $di->set('router', function(){
		        require __DIR__.'/../app/config/routes.php';

		        return $router;
		    });

		    // Database connection is created based on parameters defined in the configuration file
		    $di->set('db', function() use ($config) {
		        return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
		            "host"      => $config->database->host,
		            "username"  => $config->database->username,
		            "password"  => $config->database->password,
		            "dbname"    => $config->database->dbname
		        ));
		    });

		    //Start the session the first time a component requests the session service
		    $di->setShared('session', function() use ($config) {
		        session_name($config->session->session_name);
		        session_set_cookie_params($config->session->default_lifetime, $config->session->default_path, $config->session->default_domain);

		        $session = new Phalcon\Session\Adapter\Files();

		        if(!$session->isStarted())
		        {
		            $session->start();
		        }

		        $session->setOptions(array(
		            'uniqueId' => $config->session->uniqueId
		        ));

		        return $session;
		    });

		    $di->getSession();

		    //Set up the flash service
		    //Register the flash service with custom CSS classes
			$di->set('flash', function(){
			    $flash = new \Phalcon\Flash\Session(array(
			        'error' => 'alert alert-danger',
			        'success' => 'alert alert-success',
			        'notice' => 'alert alert-info',
			    ));

			    return $flash;
			});

		    // Start the cookies the first time a componet requests the cookie service
		    $di->set('cookies', function() use ($config) {
		        $cookies = new Phalcon\Http\Response\Cookies();

		        return $cookies;
		    });

		    // set crypt protect cookie's data by user-defined key
		    $di->set('crypt', function() use ($config) {
		        $crypt = new Phalcon\Crypt();

		        $crypt->setKey($config->crypt->key);

		        return $crypt;
		    });

		    $di->setShared('security', function(){
		        $security = new Phalcon\Security();

		        //Set the password hashing factor to 12 rounds
		        $security->setWorkFactor(12);

		        return $security;
		    });

		    //Register Volt as a service
		    $di->set('voltService', function($view, $di)
		    {
		        $volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);

		        $volt->setOptions(array(
		            "compiledPath"      => "../app/storages/views/",
		            "compiledExtension" => ".compiled",

		            'compileAlways'     => true, // local
		            'stat'              => false
		        ));

		        return $volt;
		    });

		    //Setup the view component
		    $di->set('view', function() use ($config) {
		        $view = new \Phalcon\Mvc\View();

		        $view->setViewsDir($config->application->viewsDir);

		        $view->registerEngines(array(
		            ".volt"     => 'voltService',
		            ".phtml"    => 'voltService'
		        ));

		        return $view;
		    });

		    //Setup a base URI so that all generated URIs include the "tutorial" folder
		    $di->set('url', function() use($config) {
		        $url = new \Phalcon\Mvc\Url();

		        $url->setBasePath(__DIR__.'/../');

		        $url->setBaseUri($config->application->url);

		        return $url;
		    });

		    //Handle the request
		    $application = new \Phalcon\Mvc\Application($di);

		    echo $application->handle()->getContent();
		} catch(\Phalcon\Exception $e) {
		     echo "PhalconException: ", $e->getMessage();
		} catch(\Exception $e){
		     echo "GeneralException: ", $e->getMessage();
		}
	}
}