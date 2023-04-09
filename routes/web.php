<?php

declare(strict_types=1);

use App\Http\Controllers\ChatRequestController;
use App\Orchid\Screens\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
// use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->to("admin");
});

Route::get("test", function(){
    dd( route("bot.send.message"));

});

/* 
|------------------------------------------------------------------------
|CUSTOM URL AND CONTOLLERS HERE
|------------------------------------------------------------------------
|
*/
Route::post("campaign/send", [NewsLetterController::class, "send_campaign_letter"]);
Route::get("chat-request", [ChatRequestController::class, "index"])->name("platform.chat-request");
Route::put('/chat-requests/{id}', [ChatRequestController::class, 'update_chat_request'])->name('update.chat_request');
Route::resource("wa-user","App\Http\Controllers\WaUserController");
Route::get("wa-users/list","App\Http\Controllers\WaUserController@list_contact");
Route::get("wa-user","App\Http\Controllers\WaUserController@index")->name("wa-user");
Route::post("wa-users/send/campaign","App\Http\Controllers\WaUserController@send_campaign");




/*
|--------------------------------------------------------------------------
| Dashboard Routes Starts
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', Dashboard::class)
    ->name('platform.main')->middleware("auth");

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('User'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Example screen');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', 'Idea::class','platform.screens.idea');


/*
|--------------------------------------------------------------------------
| Dashboard Routes Ends
|--------------------------------------------------------------------------
|
| 
*/

Route::group(["prefix" => "chatify"], function () {

    /*
|--------------------------------------------------------------------------
| Chatify Routes Begins
|--------------------------------------------------------------------------
|
| 
*/



    /*
* This is the main app route [Chatify Messenger]
*/
    Route::get('/', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "index"])->name(config('chatify.routes.prefix'));

    /**
     *  Fetch info for specific id [user/group]
     */
    Route::post('/idInfo', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "idFetchData"]);

    /**
     * Send message route
     */
    Route::post('/sendMessage', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "send"])->name('send.message');

    /**
     * Send message route from bot
     */
    Route::post('/bot/sendMessage', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "Botsend"])->name('bot.send.message');

    /**
     * Fetch messages
     */
    Route::post('/fetchMessages', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "fetch"])->name('fetch.messages');

    /**
     * Download attachments route to create a downloadable links
     */
    Route::get('/download/{fileName}', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "download"])->name(config('chatify.attachments.download_route_name'));

    /**
     * Authentication for pusher private channels
     */
    Route::post('/chat/auth', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "pusherAuth"])->name('pusher.auth');

    /**
     * Make messages as seen
     */
    Route::post('/makeSeen', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "seen"])->name('messages.seen');

    /**
     * Get contacts
     */
    Route::get('/getContacts', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "getContacts"])->name('contacts.get');

    /**
     * Update contact item data
     */
    Route::post('/updateContacts', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "updateContactItem"])->name('contacts.update');


    /**
     * Star in favorite list
     */
    Route::post('/star', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "favorite"])->name('star');

    /**
     * get favorites list
     */
    Route::post('/favorites', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "getFavorites"])->name('favorites');

    /**
     * Search in messenger
     */
    Route::get('/search', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "search"])->name('search');

    /**
     * Get shared photos
     */
    Route::post('/shared', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "sharedPhotos"])->name('shared');

    /**
     * Delete Conversation
     */
    Route::post('/deleteConversation', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "deleteConversation"])->name('conversation.delete');

    /**
     * Delete Message
     */
    Route::post('/deleteMessage', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "deleteMessage"])->name('message.delete');

    /**
     * Update setting
     */
    Route::post('/updateSettings', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "updateSettings"])->name('avatar.update');

    /**
     * Set active status
     */
    Route::post('/setActiveStatus', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "setActiveStatus"])->name('activeStatus.set');






    /*
* [Group] view by id
*/
    Route::get('/group/{id}', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "index"])->name('group');

    /*
* user view by id.
* Note : If you added routes after the [User] which is the below one,
* it will considered as user id.
*
* e.g. - The commented routes below :
*/
    // Route::get('/route', function(){ return 'Munaf'; }); // works as a route
    Route::get('/{id}', [\App\Http\Controllers\vendor\Chatify\MessagesController::class, "index"])->name('user');
    // Route::get('/route', function(){ return 'Munaf'; }); // works as a user id



    /*
|--------------------------------------------------------------------------
| Chatify Routes Ends
|--------------------------------------------------------------------------
|
| 
*/
});
