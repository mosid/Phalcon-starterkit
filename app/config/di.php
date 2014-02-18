<?php

/*
|--------------------------------------------------------------------------
| Config
|--------------------------------------------------------------------------
|
| When bootstrap is running, it create a config var.
| so we use $config to keep datas, you can make what you want with this.
| 
*/
$di->set('config', function() use ($config) {

	return $config;

});

/*
|--------------------------------------------------------------------------
| Url
|--------------------------------------------------------------------------
|
| Bootstrap.php guess for you the baseUrl if you no filled baseUri into
| app/config/app.php.
| 
*/
$di->set('url', function() use ($baseUrl) {

	$url = new \Phalcon\Mvc\Url();
	$url->setBaseUri($baseUrl);


	return $url;
});

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
|
| Set database as service, we use config/database.php for this.
| 
*/
$di->set('database', function() use ($config) {

    // Dynamic namespace/class
    $dbClass = 'Phalcon\\Db\\Adapter\\Pdo\\' . $config->database->adapter;

    // Run !
    return new $dbClass(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
        ));

});

/*
|--------------------------------------------------------------------------
| Volt Service
|--------------------------------------------------------------------------
|
| Volt service for view system just below
| 
*/
$di->set('voltService', function($view, $di) use ($config) {

  // Init volt engine
	$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

  // Set options to compiler
  // All volt template are compiled into app/cache/volt
	$volt->setOptions(array(

		'compiledPath' => $config->application->cacheDir . 'volt/',
		'compiledExtension' => '.volt-compiled'

		));

	return $volt;

});

/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
|
| Init view service
| 
*/
$di->set('view', function() use ($config) {

    // Init view class
	$view = new \Phalcon\Mvc\View();

    // Use app/views as dir
	$view->setViewsDir($config->application->viewsDir);

    // We want use volt with .volt extension
	$view->registerEngines(array(
		'.volt' => 'voltService'
		));

	return $view;

});


/*
|--------------------------------------------------------------------------
| Router 
|--------------------------------------------------------------------------
|
| Init router of Phalcon, require routes from user and run
| 
*/
$di->set('router', function() {

    // Init router
    $router = new Phalcon\Mvc\Router();

    // Fetch routes from user
    require(APPPATH . '/config/routes.php');

    // Inject
    return $router;

});



/*
|--------------------------------------------------------------------------
| Dispatcher
|--------------------------------------------------------------------------
|
| Register dispatcher as service and attach event on him to create
| 404 system if controller or action not found.
| 
*/
$di->set('dispatcher', function() use ($di) {

   $dispatcher = new \Phalcon\Mvc\Dispatcher();

   $evManager = $di->getShared('eventsManager');



   $evManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) use ($di) {


      switch ($exception->getCode()) {

         case Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
         case Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:

         $dispatcher->forward(
            array(
               'controller' => 'errors',
               'action'     => 'show404',
               )
            );

         return FALSE;

     }
 }
 );

   $dispatcher->setEventsManager($evManager);
   return $dispatcher;

}, TRUE

);