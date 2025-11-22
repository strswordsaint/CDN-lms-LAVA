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

// --- Authentication ---
$router->get('/', 'AuthController::login');
$router->get('/auth/login', 'AuthController::login')->name('login');
$router->get('/auth/register', 'AuthController::register')->name('register');
$router->post('/auth/process_login', 'AuthController::process_login');
$router->post('/auth/process_register', 'AuthController::process_register');
$router->get('/auth/logout', 'AuthController::logout')->name('logout');

// Google OAuth Routes
$router->get('/auth/google_login', 'AuthController::google_login')->name('google.login');
$router->get('/auth/google_callback', 'AuthController::google_callback');
$router->get('/auth/choose_role', 'AuthController::choose_role');
$router->post('/auth/complete_google_register', 'AuthController::complete_google_register');

// --- Dashboards ---
$router->get('/dashboard', 'DashboardController::index')->name('dashboard');
$router->get('/profile', 'ProfileController::index')->name('profile.index');
$router->post('/profile/update_details', 'ProfileController::update_details')->name('profile.update_details');
$router->post('/profile/update_password', 'ProfileController::update_password')->name('profile.update_password');
$router->get('/about', 'PageController::about')->name('about');

/*
| -------------------------------------------------------------------
| STUDENT ROUTES
| -------------------------------------------------------------------
*/
$router->get('/courses/all', 'StudentController::browse_courses')->name('courses.all');
$router->post('/courses/enroll', 'StudentController::enroll');
$router->get('/courses/my', 'StudentController::my_courses')->name('courses.my');
$router->get('/my-courses/{id}', 'StudentController::view_course')->where_number('id');

// Assignments & Activities (Student)
$router->get('/my-courses/{id}/assignments', 'StudentController::view_assignments')->where_number('id');
$router->get('/assignment/{assign_id}', 'StudentController::view_assignment')->where_number('assign_id');
$router->post('/assignment/{assign_id}/submit', 'StudentController::submit_assignment')->where_number('assign_id');
$router->post('/assignment/unsubmit/{sub_id}', 'StudentController::unsubmit_assignment')->where_number('sub_id');
$router->get('/my-assignments', 'StudentController::view_all_assignments')->name('student.assignments');
$router->get('/my-activities', 'StudentController::my_activities')->name('student.activities');

// Discussions
$router->get('/my-courses/{id}/discussions', 'StudentController::view_discussions')->where_number('id');
$router->get('/discussion/{disc_id}', 'StudentController::view_discussion')->where_number('disc_id');
$router->post('/discussion/{disc_id}/post', 'StudentController::post_reply')->where_number('disc_id');

// Grades & Calendar
$router->get('/my-grades', 'StudentController::view_grades');
$router->get('/my-calendar', 'CalendarController::index')->name('student.calendar.index');
$router->post('/courses/leave/{id}', 'StudentController::leave_course')->where_number('id')->name('courses.leave');


/*
| -------------------------------------------------------------------
| TEACHER / INSTRUCTOR ROUTES
| -------------------------------------------------------------------
*/
// Course Management
$router->get('/courses', 'CourseController::index')->name('courses.index');
$router->get('/courses/create', 'CourseController::create')->name('courses.create');
$router->post('/courses/store', 'CourseController::store')->name('courses.store');
$router->get('/courses/show/{id}', 'CourseController::show')->where_number('id')->name('courses.show');
$router->get('/courses/edit/{id}', 'CourseController::edit')->where_number('id')->name('courses.edit');
$router->post('/courses/update/{id}', 'CourseController::update')->where_number('id')->name('courses.update');
$router->post('/courses/delete/{id}', 'CourseController::delete')->where_number('id')->name('courses.delete');

// Content Creation (Assignments/Announcements/Activities)
$router->get('/courses/{id}/assignments/create', 'AssignmentController::create')->where_number('id');
$router->post('/courses/{id}/announcement/store', 'AssignmentController::store_announcement')->where_number('id');
$router->post('/courses/{id}/post/store', 'AssignmentController::store_announcement_or_activity')->where_number('id');
$router->get('/activities/all', 'ActivityController::view_all')->name('activities.all');

// Calendar
$router->get('/calendar', 'CalendarController::index')->name('calendar.index');
$router->get('/calendar/events', 'CalendarController::get_events')->name('calendar.events');

// Assignment Grading & Management
$router->post('/courses/{id}/assignments/store', 'AssignmentController::store')->where_number('id');
$router->get('/assignments/edit/{assign_id}', 'AssignmentController::edit')->where_number('assign_id');
$router->post('/assignments/update/{assign_id}', 'AssignmentController::update')->where_number('assign_id');
$router->post('/assignments/delete/{assign_id}', 'AssignmentController::delete')->where_number('assign_id');
$router->get('/assignments/{assign_id}/submissions', 'AssignmentController::view_submissions')->where_number('assign_id');
$router->get('/assignments/all', 'AssignmentController::view_all')->name('assignments.all');
$router->get('/submissions/{sub_id}/grade', 'AssignmentController::show_grade_form')->where_number('sub_id');
$router->post('/submissions/{sub_id}/grade', 'AssignmentController::process_grade')->where_number('sub_id');
$router->get('/submissions/ungraded', 'AssignmentController::view_ungraded')->name('submissions.ungraded');


/*
| -------------------------------------------------------------------
| QUIZ ROUTES (SurveyJS)
| -------------------------------------------------------------------
*/
// Teacher Actions
$router->get('/courses/{id}/quizzes/create', 'QuizController::create')->where_number('id');
$router->post('/quizzes/store', 'QuizController::store');
$router->get('/quizzes/edit/{id}', 'QuizController::edit')->where_number('id');
$router->post('/quizzes/update/{id}', 'QuizController::update')->where_number('id');

// Student Actions
$router->get('/quiz/{id}', 'QuizController::take')->where_number('id');
$router->post('/quizzes/submit/{id}', 'QuizController::submit_results')->where_number('id');
$router->get('/quiz/{quiz_id}/results', 'StudentController::view_quiz_results')->where_number('quiz_id');


/*
| -------------------------------------------------------------------
| SHARED ROUTES (Discussions, Resources, Enrollments, Replies)
| -------------------------------------------------------------------
*/
// Discussion
$router->get('/courses/{id}/discussions/create', 'DiscussionController::create')->where_number('id');
$router->post('/courses/{id}/discussions/store', 'DiscussionController::store')->where_number('id');

// Resource Management
$router->post('/courses/{id}/materials/upload', 'ResourceController::upload')->where_number('id');
$router->post('/materials/delete/{id}', 'ResourceController::delete')->where_number('id');

// Enrollment Management
$router->get('/courses/{id}/enrollments', 'CourseController::manage_enrollments')->where_number('id')->name('courses.enrollments');
$router->post('/enrollments/approve/{enrollment_id}', 'CourseController::approve_enrollment')->where_number('enrollment_id')->name('enrollments.approve');
$router->post('/enrollments/reject/{enrollment_id}', 'CourseController::reject_enrollment')->where_number('enrollment_id')->name('enrollments.reject');
$router->post('/enrollments/remove/{enrollment_id}', 'CourseController::remove_enrollment')->where_number('enrollment_id')->name('enrollments.remove');

// Replies (AJAX)
$router->get('/post/{id}/replies', 'ReplyController::get')->where_number('id');
$router->post('/post/{id}/reply', 'ReplyController::store')->where_number('id');


/*
| -------------------------------------------------------------------
| ADMIN ROUTES
| -------------------------------------------------------------------
*/
// Admin User Management
$router->get('/admin/users', 'AdminController::manage_users')->name('admin.users');
$router->get('/admin/user/create', 'AdminController::create_user')->name('admin.user.create');
$router->post('/admin/user/store', 'AdminController::store_user')->name('admin.user.store');
$router->get('/admin/user/edit/{id}', 'AdminController::edit_user')->where_number('id')->name('admin.user.edit');
$router->post('/admin/user/update/{id}', 'AdminController::update_user')->where_number('id')->name('admin.user.update');
$router->post('/admin/user/delete/{id}', 'AdminController::delete_user')->where_number('id')->name('admin.user.delete');
$router->post('/admin/teacher/approve/{id}', 'AdminController::approve_teacher')->where_number('id')->name('admin.teacher.approve');

// Admin User Suspension
$router->get('/admin/user/suspend/{id}', 'AdminController::suspend_user_form')->where_number('id')->name('admin.user.suspend');
$router->post('/admin/user/process_suspension/{id}', 'AdminController::process_suspension')->where_number('id')->name('admin.user.process_suspend');
$router->get('/admin/user/reactivate/{id}', 'AdminController::reactivate_user')->where_number('id')->name('admin.user.reactivate');

// Admin Course Management
$router->get('/admin/courses', 'AdminController::manage_courses')->name('admin.courses.index');
$router->get('/admin/courses/create', 'AdminController::create_course')->name('admin.courses.create'); // Moved here
$router->post('/admin/courses/store', 'AdminController::store_course')->name('admin.courses.store');   // Moved here
$router->get('/admin/courses/view/{id}', 'AdminController::view_course')->where_number('id')->name('admin.courses.view');
$router->get('/admin/courses/edit/{id}', 'AdminController::edit_course')->where_number('id')->name('admin.courses.edit');
$router->post('/admin/courses/update/{id}', 'AdminController::update_course')->where_number('id')->name('admin.courses.update');
$router->post('/admin/courses/remove_student/{id}', 'AdminController::remove_student_from_course')->where_number('id')->name('admin.courses.remove_student');
$router->post('/admin/courses/delete/{id}', 'AdminController::delete_course')->where_number('id')->name('admin.courses.delete');

// Admin System Tools
$router->get('/admin/announcements', 'AdminController::manage_site_announcements')->name('admin.announcements.index');
$router->post('/admin/announcements/store', 'AdminController::store_site_announcement')->name('admin.announcements.store');
$router->post('/admin/announcements/delete/{id}', 'AdminController::delete_site_announcement')->where_number('id')->name('admin.announcements.delete');
$router->get('/admin/reports', 'AdminController::general_reports')->name('admin.reports');
?>