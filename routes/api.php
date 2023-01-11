<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'system'
], function () {
    Route::post('admin/login', ['as' => 'system.admin.login', 'uses' => 'AuthController@adminProcess']);
    Route::post('/login', ['as' => 'system.login', 'uses' => 'AuthController@process']);
    Route::post('/redirect/auth', ['as' => 'system.redirect', 'uses' => 'AuthController@auth']);
});

Route::group([
    'prefix' => 'system', 'middleware' => 'auth:api'
], function () {
    Route::post('/logout', ['as' => 'system.logout', 'uses' => 'AuthController@logout']);
});

Route::group([
    'prefix' => 'users_info'
], function () {

    Route::post('create-account', ['as' => 'user_info.create-account', 'uses' => 'UserInfoController@createWithCredentials']);
    Route::post('suggestion', ['as' => 'user_info.suggestion', 'uses' => 'UserInfoController@usernameSuggestions']);
    Route::post('checker', ['as' => 'user_info.checker', 'uses' => 'UserInfoController@checker']);
});

Route::group([
    'prefix' => 'users_info', 'middleware' => 'auth:api'
], function () {

    Route::post('save', ['as' => 'user_info.save', 'uses' => 'UserInfoController@store']);
    Route::post('contact_us', ['as' => 'user_info.contact_us', 'uses' => 'UserInfoController@contactUs']);
    Route::patch('{id}', ['as' => 'user_info.update', 'uses' => 'UserInfoController@update']);
    Route::get('{id}', ['as' => 'user_info.get', 'uses' => 'UserInfoController@show']);
    Route::get('details/{id}', ['as' => 'user_info.details.id', 'uses' => 'UserInfoController@withSettings']);
});

Route::group([
    'prefix' => 'admin', 'middleware' => 'auth:api'
], function () {

    Route::get('list', ['as' => 'user_info.admin.list', 'uses' => 'UserInfoController@adminlist']);
    Route::get('user/list', ['as' => 'user_info.admin.search', 'uses' => 'UserInfoController@userList']);
    Route::get('contact_us/list', ['as' => 'contact_us.admin.list', 'uses' => 'InboxController@contactUsList']);
    Route::delete('user/{id}', ['as' => 'user_info.admin.delete', 'uses' => 'UserInfoController@destroy']);
    Route::patch('action', ['as' => 'user_info.admin.action', 'uses' => 'UserInfoController@action']);
    Route::patch('read/{id}', ['as' => '.admin.contact_us.read', 'uses' => 'InboxController@read']);
});


Route::group([
    'prefix' => 'contact',
    'middleware' => 'auth:api'
], function () {

    Route::post('create', ['as' => 'contacts.add', 'uses' => 'ContactsController@create']);
    Route::patch('{id}', ['as' => 'contacts.update', 'uses' => 'ContactsController@update']);
    Route::delete('{id}', ['as' => 'contacts.delete', 'uses' => 'ContactsController@destroy']);
    Route::delete('user/{user_info_id}', ['as' => 'contacts.delete.info', 'uses' => 'ContactsController@deleteByUserInfoId']);
    Route::get('{id}', ['as' => 'contacts.get', 'uses' => 'ContactsController@show']);
    Route::get('user/{user_info_id}', ['as' => 'contacts.getUserInfoId', 'uses' => 'ContactsController@getByUserInfoID']);
    Route::get('search/list', ['as' => 'contacts.search', 'uses' => 'ContactsController@search']);
    Route::patch('pinned/update', ['as' => 'contacts.pin', 'uses' => 'ContactsController@pin']);
    Route::get('pinned/{user_info_id}', ['as' => 'contacts.getpinnedid', 'uses' => 'ContactsController@getPinnedID']);
    Route::get('unpinned/{user_info_id}', ['as' => 'contacts.getunpinnedid', 'uses' => 'ContactsController@getUnpinnedID']);
});

Route::group([
    'prefix' => 'notification',
    'middleware' => 'auth:api'
], function () {

    Route::post('create', ['as' => 'notifications.add', 'uses' => 'NotificationsController@create']);
    Route::post('save', ['as' => 'notifications.save', 'uses' => 'NotificationsController@store']);
    Route::patch('read/{id}', ['as' => 'notifications.read', 'uses' => 'NotificationsController@read']);
    Route::delete('{id}', ['as' => 'notifications.delete', 'uses' => 'NotificationsController@destroy']);
    Route::delete('user/{user_info_id}', ['as' => 'notifications.delete.user', 'uses' => 'NotificationsController@deleteByUserInfoId']);
    Route::get('list/user/{user_info_id}', ['as' => 'notifications.get', 'uses' => 'NotificationsController@getByUserInfoID']);
});

Route::group([
    'prefix' => 'message',
    'middleware' => 'auth:api'
], function () {
    Route::post('send/new', ['as' => 'message.new', 'uses' => 'MessagesController@new']);
    Route::post('send', ['as' => 'message.send', 'uses' => 'MessagesController@send']);
    Route::patch('update', ['as' => 'message.update', 'uses' => 'MessagesController@update']);
    Route::delete('{id}', ['as' => 'message.delete', 'uses' => 'MessagesController@destroy']);
    Route::delete('delete/timer', ['as' => 'message.deletebytimer', 'uses' => 'MessagesController@deleteByTimer']);
    Route::delete('user/{user_info_id}', ['as' => 'message.delete.user', 'uses' => 'MessagesController@deleteByUserInfoId']);
    Route::get('conversation/{conversation_id}', ['as' => 'message.getConversationId', 'uses' => 'MessagesController@getConversationId']);
});

Route::group([
    'prefix' => 'account_verification',
    'middleware' => 'auth:api'
], function () {

    Route::patch('{id}', ['as' => 'account_verification.update', 'uses' => 'AccountVerificationController@update']);
});


Route::group([
    'prefix' => 'file',
    'middleware' => 'auth:api'
], function () {

    Route::post('create', ['as' => 'file.add', 'uses' => 'FilesController@create']);
    Route::post('save', ['as' => 'file.save', 'uses' => 'FilesController@store']);
    Route::patch('{id}', ['as' => 'file.update', 'uses' => 'FilesController@update']);
    Route::delete('{id}', ['as' => 'file.delete', 'uses' => 'FilesController@destroy']);
    Route::get('list', ['as' => 'file.list', 'uses' => 'FilesController@list']);
    Route::get('{id}', ['as' => 'file.get', 'uses' => 'FilesController@show']);
});

Route::group([
    'prefix' => 'user_setting',
    'middleware' => 'auth:api'
], function () {

    Route::post('create', ['as' => 'user_setting.add', 'uses' => 'UserSettingController@create']);
    Route::post('save', ['as' => 'user_setting.save', 'uses' => 'UsersettingController@store']);
    Route::post('update', ['as' => 'user_setting.photo.update', 'uses' => 'UserSettingController@updateUserSetting']);
    Route::patch('status', ['as' => 'user_setting.status', 'uses' => 'UserSettingController@status']);
    Route::patch('timer', ['as' => 'user_setting.timer', 'uses' => 'UserSettingController@updateTimer']);
    Route::get('{user_info_id}', ['as' => 'user_setting.get', 'uses' => 'UserSettingController@getByUserInfoID']);
});

Route::group([
    'prefix' => 'conversation',
    'middleware' => 'auth:api'
], function () {

    Route::get('check/user', ['as' => 'conversation.check', 'uses' => 'ConversationController@check']);
    Route::get('view', ['as' => 'conversation.view', 'uses' => 'ConversationController@view']);
    Route::get('detailed/{id}', ['as' => 'conversation.datailed', 'uses' => 'ConversationController@detailedById']);
    Route::get('{user_info_id}', ['as' => 'conversation.get', 'uses' => 'ConversationController@getByUserInfoId']);
    Route::get('groups/{user_info_id}', ['as' => 'conversation.get.groups', 'uses' => 'ConversationController@getGroupsByUserInfoId']);
    Route::delete('{id}', ['as' => 'conversation.delete', 'uses' => 'ConversationController@destroy']);
    Route::delete('user/{user_info_id}', ['as' => 'conversation.delete.all', 'uses' => 'ConversationController@deleteByUserInfoId']);
    Route::patch('update', ['as' => 'conversation.update', 'uses' => 'ConversationController@update']);
    Route::post('photo/update', ['as' => 'conversation.photo.update', 'uses' => 'ConversationController@updatePhoto']);
});

Route::group([
    'prefix' => 'conversation_user',
    'middleware' => 'auth:api'
], function () {

    Route::post('create', ['as' => 'conversation_user.add', 'uses' => 'ConversationUserController@create']);
    Route::delete('{id}', ['as' => 'conversation_user.delete', 'uses' => 'ConversationUserController@destroy']);
});

Route::post('/save-token', [App\Http\Controllers\AuthController::class, 'saveToken'])->name('save-token');
Route::get('/send-notification', [App\Http\Controllers\AuthController::class, 'sendNotification'])->name('send.notification');
Route::get('/send-notification-legacy', [App\Http\Controllers\AuthController::class, 'sendNotificationLegacy'])->name('send.notification.legacy');

Route::get('/connect/access-token', [App\Http\Controllers\AuthController::class, 'twilioAccessToken'])->name('connect.accesstoken');
Route::post('/connect/video', [App\Http\Controllers\ConversationController::class, 'createVideoRoom'])->name('connect.video');
Route::post('/disconnect/video', [App\Http\Controllers\ConversationController::class, 'endRoom'])->name('disconnect.video');
Route::post('/room/webhooks', [App\Http\Controllers\ConversationController::class, 'videoWebhooks'])->name('room.webhooks');
Route::post('/room/join/user', [App\Http\Controllers\ConversationController::class, 'generateAccessToken'])->name('room.generate.new');

Route::group([
    'prefix' => 'test',
    'middleware' => 'auth:api'
], function () {

    Route::post('/headers', ['as' => 'test.headers', 'uses' => 'TestController@testHeader']);
});
