<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/

// Authentication routes
$router->match('/', 'AuthController::login', ['GET','POST']);
$router->match('auth/login', 'AuthController::login', ['GET','POST']);
$router->match('auth/register', 'AuthController::register', ['GET','POST']);
$router->get('auth/logout', 'AuthController::logout');

// Dashboard routes
$router->get('dashboard', 'DashboardController::index');
$router->match('dashboard/update_profile', 'DashboardController::update_profile', ['GET','POST']);
$router->post('dashboard/upload_profile_image', 'DashboardController::upload_profile_image');

// Student CRUD routes (Admin only)
$router->get('students', 'StudentsController::index');
$router->get('students/create', 'StudentsController::create');
$router->post('students/store', 'StudentsController::store');
$router->get('students/edit/{id}', 'StudentsController::edit');
$router->post('students/update/{id}', 'StudentsController::update');
$router->get('students/softdelete/{id}', 'StudentsController::delete');

// Soft delete routes
$router->get('students/deleted', 'StudentsController::deleted');
$router->get('students/restore/{id}', 'StudentsController::restore');
$router->get('students/hard_delete/{id}', 'StudentsController::hard_delete');