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

// --- Authentication (Works) ---
$router->get('/', 'AuthController::login'); // Default to login
$router->get('/auth/login', 'AuthController::login')->name('login');
$router->get('/auth/register', 'AuthController::register')->name('register');
$router->post('/auth/process_login', 'AuthController::process_login');
$router->post('/auth/process_register', 'AuthController::process_register');
$router->get('/auth/logout', 'AuthController::logout')->name('logout');

// Google OAuth Routes
$router->get('/auth/google_login', 'AuthController::google_login')->name('google.login');
$router->get('/auth/google_callback', 'AuthController::google_callback'); // Google redirects here
// Add these near your other auth routes
$router->get('/auth/choose_role', 'AuthController::choose_role');
$router->post('/auth/complete_google_register', 'AuthController::complete_google_register');

// --- Dashboards (Works) ---
$router->get('/dashboard', 'DashboardController::index')->name('dashboard');

/*
| -------------------------------------------------------------------
| STUDENT ROUTES
| -------------------------------------------------------------------
*/

// --- Enrollment (NEW) ---
$router->get('/courses/all', 'StudentController::browse_courses')->name('courses.all'); // Browse all courses
$router->post('/courses/enroll', 'StudentController::enroll'); // Form submission with a course code
$router->get('/courses/my', 'StudentController::my_courses')->name('courses.my'); // View enrolled courses
// --- Course Content Views (NEW) ---
// View a single enrolled course (dashboard)
$router->get('/my-courses/{id}', 'StudentController::view_course')->where_number('id');
// View assignments for that course
$router->get('/my-courses/{id}/assignments', 'StudentController::view_assignments')->where_number('id');
// View a single assignment (and submit)
$router->get('/assignment/{assign_id}', 'StudentController::view_assignment')->where_number('assign_id');
$router->post('/assignment/{assign_id}/submit', 'StudentController::submit_assignment')->where_number('assign_id');
$router->post('/assignment/unsubmit/{sub_id}', 'StudentController::unsubmit_assignment')->where_number('sub_id');

// view assignments list
$router->get('/my-assignments', 'StudentController::view_all_assignments')->name('student.assignments');
// --- Student Quiz Taking (NEW) ---
$router->get('/my-courses/{id}/quizzes', 'StudentController::view_quizzes')->where_number('id');
$router->get('/quiz/{quiz_id}/take', 'StudentController::take_quiz')->where_number('quiz_id');
$router->post('/quiz/{quiz_id}/submit', 'StudentController::submit_quiz')->where_number('quiz_id');
$router->get('/quiz/{quiz_id}/results', 'StudentController::view_quiz_results')->where_number('quiz_id');

// --- Student Discussions (NEW) ---
$router->get('/my-courses/{id}/discussions', 'StudentController::view_discussions')->where_number('id');
$router->get('/discussion/{disc_id}', 'StudentController::view_discussion')->where_number('disc_id');
$router->post('/discussion/{disc_id}/post', 'StudentController::post_reply')->where_number('disc_id');

// --- Student Grades (NEW) ---
$router->get('/my-grades', 'StudentController::view_grades');
// ===================================================================


/*
| -------------------------------------------------------------------
| TEACHER / INSTRUCTOR ROUTES
| -------------------------------------------------------------------
*/

// --- Course Management (Works) ---
// List courses for the logged-in teacher
$router->get('/courses', 'CourseController::index')->name('courses.index');
// Show form to create a new course
$router->get('/courses/create', 'CourseController::create')->name('courses.create');
// Store the new course in the database
$router->post('/courses/store', 'CourseController::store')->name('courses.store');
// Show the "manage" page for a single course (Assignments, Quizzes, etc.)
$router->get('/courses/show/{id}', 'CourseController::show')->where_number('id')->name('courses.show');
// Show the form to edit a course
$router->get('/courses/edit/{id}', 'CourseController::edit')->where_number('id')->name('courses.edit');
// Update the course in the database
$router->post('/courses/update/{id}', 'CourseController::update')->where_number('id')->name('courses.update');
// Delete a course
$router->post('/courses/delete/{id}', 'CourseController::delete')->where_number('id')->name('courses.delete');

// --- Assignment Management (NEW) ---
// These routes are nested under a course
$router->get('/courses/{id}/assignments/create', 'AssignmentController::create')->where_number('id');
$router->post('/courses/{id}/assignments/store', 'AssignmentController::store')->where_number('id');
$router->get('/assignments/edit/{assign_id}', 'AssignmentController::edit')->where_number('assign_id');
$router->post('/assignments/update/{assign_id}', 'AssignmentController::update')->where_number('assign_id');
$router->post('/assignments/delete/{assign_id}', 'AssignmentController::delete')->where_number('assign_id');
$router->get('/assignments/{assign_id}/submissions', 'AssignmentController::view_submissions')->where_number('assign_id');
$router->get('/assignments/all', 'AssignmentController::view_all')->name('assignments.all');
// --- Assignment Grading (NEW) ---
$router->get('/submissions/{sub_id}/grade', 'AssignmentController::show_grade_form')->where_number('sub_id');
$router->post('/submissions/{sub_id}/grade', 'AssignmentController::process_grade')->where_number('sub_id');
$router->get('/submissions/ungraded', 'AssignmentController::view_ungraded')->name('submissions.ungraded');

// --- Quiz Management (NEW) ---
$router->get('/courses/{id}/quizzes/create', 'QuizController::create')->where_number('id');
$router->post('/courses/{id}/quizzes/store', 'QuizController::store')->where_number('id');
$router->get('/quizzes/{quiz_id}', 'QuizController::show')->where_number('quiz_id'); // Manage quiz (add questions)
$router->post('/quizzes/{quiz_id}/questions/store', 'QuizController::store_question')->where_number('quiz_id');
$router->get('/quizzes/results/{quiz_id}', 'QuizController::view_results')->where_number('quiz_id');

// --- Discussion Management (NEW) ---
$router->get('/courses/{id}/discussions/create', 'DiscussionController::create')->where_number('id');
$router->post('/courses/{id}/discussions/store', 'DiscussionController::store')->where_number('id');

// --- Resource Management (NEW) ---
$router->post('/courses/{id}/materials/upload', 'ResourceController::upload')->where_number('id');
$router->post('/materials/delete/{id}', 'ResourceController::delete')->where_number('id');

// --- Enrollment Management (NEW - Teacher Side) ---
$router->get('/courses/{id}/enrollments', 'CourseController::manage_enrollments')->where_number('id')->name('courses.enrollments');
$router->post('/enrollments/approve/{enrollment_id}', 'CourseController::approve_enrollment')->where_number('enrollment_id')->name('enrollments.approve');
$router->post('/enrollments/reject/{enrollment_id}', 'CourseController::reject_enrollment')->where_number('enrollment_id')->name('enrollments.reject');
$router->post('/enrollments/remove/{enrollment_id}', 'CourseController::remove_enrollment')->where_number('enrollment_id')->name('enrollments.remove');
$router->post('/courses/leave/{id}', 'StudentController::leave_course')->where_number('id')->name('courses.leave');

/*
| -------------------------------------------------------------------
| ADMIN ROUTES
| -------------------------------------------------------------------
*/
$router->get('/admin/users', 'AdminController::manage_users')->name('admin.users');
$router->get('/admin/user/edit/{id}', 'AdminController::edit_user')->where_number('id')->name('admin.user.edit');
$router->post('/admin/user/update/{id}', 'AdminController::update_user')->where_number('id')->name('admin.user.update');
$router->post('/admin/user/delete/{id}', 'AdminController::delete_user')->where_number('id')->name('admin.user.delete');

$router->get('/admin/courses', 'AdminController::manage_courses')->name('admin.courses.index');
$router->get('/admin/courses/view/{id}', 'AdminController::view_course')->where_number('id')->name('admin.courses.view');
$router->get('/admin/courses/edit/{id}', 'AdminController::edit_course')->where_number('id')->name('admin.courses.edit');
$router->post('/admin/courses/update/{id}', 'AdminController::update_course')->where_number('id')->name('admin.courses.update');
$router->post('/admin/courses/remove_student/{id}', 'AdminController::remove_student_from_course')->where_number('id')->name('admin.courses.remove_student');

$router->post('/admin/courses/delete/{id}', 'AdminController::delete_course')->where_number('id')->name('admin.courses.delete');


?>