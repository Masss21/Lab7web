<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// — Publik —
// Menampilkan daftar dan detail artikel
$routes->get('/artikel',           'Artikel::index');
$routes->get('/artikel/(:segment)', 'Artikel::view/$1');

// Halaman statis lain
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs',    'Page::faqs');
$routes->get('/tos',     'Page::tos');

// — Admin (protected by 'auth' filter) —
// Admin (protected by 'auth' filter)
// — Admin (protected by 'auth' filter) —
$routes->group('admin', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function($routes) {
    $routes->get(   'artikel',                   'Artikel::admin_index' );
    $routes->get(   'artikel/add',               'Artikel::add' );
    $routes->post(  'artikel/add',               'Artikel::save_artikel' );
    $routes->get(   'artikel/edit/(:num)',       'Artikel::edit/$1' );
    $routes->post(  'artikel/update/(:num)',     'Artikel::update/$1' ); // <- ini YANG BENAR
    $routes->get(   'artikel/delete/(:num)',     'Artikel::delete/$1' );
});



$routes->resource('post');


// — AJAX Artikel Management —
$routes->group('ajax', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'AjaxController::index');
    $routes->get('getData', 'AjaxController::getData');
    $routes->get('getCategories', 'AjaxController::getCategories');
    $routes->post('create', 'AjaxController::create');
    $routes->post('update/(:num)', 'AjaxController::update/$1');
    $routes->post('removeImage/(:num)', 'AjaxController::removeImage/$1');
    $routes->delete('delete/(:num)', 'AjaxController::delete/$1');
});


// Rute login user
$routes->get(  'user/login', 'User::login');
$routes->post( 'user/login', 'User::login');

$routes->get('login', 'User::login');
$routes->post('login', 'User::login');
$routes->get('logout', 'User::logout');



// Debug hash (opsional)
$routes->get('/hash', function () {
    echo password_hash('300105', PASSWORD_DEFAULT);
});

// Nonaktifkan auto-routing untuk keamanan
$routes->setAutoRoute(false);
