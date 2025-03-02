<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
 
   $routes->connect('/prizelist', ['controller' => 'Prize', 'action' => 'prizelist']);

    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
    $routes->connect('/data_analysis', ['controller' => 'DataAnalysis', 'action' => 'index']);
    //$routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
    // $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    $routes->connect('/new_survey', ['controller' => 'Admins', 'action' => 'new_survey', 'new_survey']);

    $routes->connect('/add_survey', ['controller' => 'Surveys', 'action' => 'add']);
    $routes->connect('/view_survey/*', ['controller' => 'Surveys', 'action' => 'view']);

    $routes->connect('/register', ['controller' => 'Users', 'action' => 'add']);

    $routes->connect('/login', ['controller' => 'UserLogin', 'action' => 'login']);

    $routes->connect('/spin', ['controller' => 'Luckydraw', 'action' => 'spin']);

    $routes->connect('/add_answer', ['controller' => 'Answers', 'action' => 'add']);

    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);
    $routes->connect('/answer', ['controller' => 'Answer', 'action' => 'index']);

    $routes->connect('/', ['controller' => 'Products', 'action' => 'index']);
    $routes->connect('/survey-summary', ['controller' => 'Surveys', 'action' => 'index']);
    // $routes->connect('/add', ['controller' => 'Products', 'action' => 'add']);
    // $routes->connect('/edit/*', ['controller' => 'Products', 'action' => 'edit']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});


Router::prefix('admin', function (RouteBuilder $routes) {
  
    $routes->connect('/', ['controller' => 'Luckydraw', 'action' => 'index']);
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('admin', function (RouteBuilder $routes) {
  
    $routes->connect('/prize', ['controller' => 'Prizes', 'action' => 'prizelist']);
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('user', function (RouteBuilder $routes) {
  
    $routes->connect('/dashboard', ['controller' => 'Prizes', 'action' => 'dashboard']);
    $routes->fallbacks(DashedRoute::class);
});
Router::prefix('user', function (RouteBuilder $routes) {
  
    $routes->connect('/', ['controller' => 'Prizes', 'action' => 'spin']);
    $routes->fallbacks(DashedRoute::class);
});



/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
