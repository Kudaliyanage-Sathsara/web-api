<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| Custom Routes
| -------------------------------------------------------------------------
*/

// Auth routes
$route['auth/login'] = 'auth/login';
$route['auth/register'] = 'auth/register';
$route['auth/process_login'] = 'auth/process_login';
$route['auth/process_register'] = 'auth/process_register';
$route['auth/logout'] = 'auth/logout';
$route['auth/forgot_password'] = 'auth/forgot_password';
$route['auth/process_forgot_password'] = 'auth/process_forgot_password';
$route['auth/reset_password'] = 'auth/reset_password';
$route['auth/process_reset_password'] = 'auth/process_reset_password';

// Profile routes
$route['profile'] = 'profile/index';
$route['profile/edit'] = 'profile/edit';
$route['profile/update'] = 'profile/update';
$route['profile/add_linkedin'] = 'profile/add_linkedin';
$route['profile/delete_linkedin/(:num)'] = 'profile/delete_linkedin/$1';
$route['profile/add_section/(:any)'] = 'profile/add_section/$1';
$route['profile/update_section/(:any)'] = 'profile/update_section/$1';
$route['profile/delete_section/(:any)'] = 'profile/delete_section/$1';

// Dashboard routes
$route['dashboard'] = 'dashboard/index';
$route['dashboard/graphs'] = 'dashboard/graphs';
$route['dashboard/alumni_list'] = 'dashboard/alumni_list';

// API routes
$route['api/alumni'] = 'api/alumni/index';
$route['api/alumni/stats'] = 'api/alumni/stats';
$route['api/alumni/export_csv'] = 'api/alumni/export_csv';
