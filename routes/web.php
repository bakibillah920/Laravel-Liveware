<?php

use Illuminate\Support\Facades\Route;
    
// Route::get('/{any}', 'HomeController@abort_404')->where('any', '.*');

Route::get('/imdb-detail-insert','App\LandingController@imdb_detail_insert')->name('imdb-detail-insert');
    
// Route::get('/{any}', 'HomeController@abort_404')->where('any', '.*');
    
// all images will be optimized automatically
Route::middleware('optimizeImages')->group(function () {

    //---------------- routes for unauthenticated users Start ----------------//

    Route::get('shouts', 'App\ShoutController@index');
// shout routes for ajax reload
    Route::post('shouts/newShout', 'App\ShoutController@newShout')->name('shouts.newShout');
    Route::post('shouts/delete', 'App\ShoutController@delete')->name('shouts.delete');
    Route::post('shouts/delete/{id}', 'App\ShoutController@deleteSingle')->name('shouts.delete.single');

// Auth routes
    Auth::routes();

// redirect to landing page if route is '/home'
    Route::redirect('/home', '/');

    Route::group(['namespace'=>'App'], function () {

        // landing routes
        Route::get('/', 'LandingController@index')->name('/');

        // notice-box routes
        Route::get('/notice-box', 'SettingController@noticeBoxEdit')->name('noticeBoxEdit');
        Route::post('/notice-box', 'SettingController@noticeBoxUpdate')->name('noticeBoxUpdate')->middleware('auth');

        // dmca routes
        Route::get('/dmca', 'SettingController@dmcaIndex')->name('dmca.index');
        Route::get('/dmca-update', 'SettingController@dmcaEdit')->name('dmca.edit')->middleware('auth');
        Route::post('/dmca-update', 'SettingController@dmcaUpdate')->name('dmca.update')->middleware('auth');

        // rules routes
        Route::get('/rules', 'SettingController@rulesIndex')->name('rules.index');
        Route::get('/rules-update', 'SettingController@rulesEdit')->name('rules.edit')->middleware('auth');
        Route::post('/rules-update', 'SettingController@rulesUpdate')->name('rules.update')->middleware('auth');

        // category routes
        Route::get('/category/{category}', 'LandingController@categories')->name('landing.categories.categories');
        Route::get('/category/{category}/{subcategory}', 'LandingController@category_index')->name('landing.categories.index');
        Route::get('/category/{category}/{subcategory}/{torrentUri}', 'LandingController@category_show')->name('landing.categories.show')->middleware('auth');

        Route::post('/search', 'LandingController@search_index')->name('landing.search.index');
//    Route::get('/search/{category}/{subcategory}/{torrentUri}', 'LandingController@search_index')->name('landing.search.index');

        // help routes [ if !Auth::user() than it's guest! ]
        Route::resource('help', 'HelpController')->middleware('auth');
        Route::patch('help/{id}/read', 'HelpController@read')->name('help.read')->middleware('auth');

    });

//---------------- routes for unauthenticated users End ----------------//

    Route::group(['namespace'=>'App', 'middleware' => 'auth'], function () {

        //----------------- ACL Start -----------------//

        Route::group(['namespace'=>'ACL'], function () {

            Route::post('/deleteApp', 'UserController@deleteApp')->name('deleteApp');

            Route::resource('/users', 'UserController');

            Route::resource('/roles', 'RoleController');

            Route::resource('/permissions', 'PermissionController');

            //---------------- Authorization Start ----------------//

            Route::get('/assign-user-role', 'AuthorizationController@view_user_roles')->name('assign-user-role');
            Route::post('/assign-user-role/{user_id}/{role_id}', 'AuthorizationController@update_user_roles')->name('user-role');

            Route::get('/assign-role-permission', 'AuthorizationController@view_role_permissions')->name('assign-role-permission');
            Route::get('/assign-role-permission/{role_id}/{permission_id}', 'AuthorizationController@edit_role_permissions')->name('edit-role-permission');
            Route::post('/assign-role-permission', 'AuthorizationController@update_role_permissions')->name('role-permission');

            Route::get('/assign-user-permission', 'AuthorizationController@view_user_permissions')->name('assign-user-permission');
            Route::get('/assign-user-permission/{user_id}/{permission_id}', 'AuthorizationController@edit_user_permissions')->name('edit-user-permission');
            Route::post('/assign-user-permission', 'AuthorizationController@update_user_permissions')->name('user-permission');

            //----------------- Authorization End -----------------//

        });

        //----------------- ACL End -----------------//

        // user profile
        Route::get('/profile/{username}', 'ProfileController@show')->name('profile.show');
        Route::get('/profile/{username}/edit', 'ProfileController@edit')->name('profile.edit');
        Route::patch('/profile/{username}/update', 'ProfileController@update')->name('profile.update');

        // update password
        Route::get('/update-password', 'ProfileController@editPassword')->name('editPassword');
        Route::patch('/update-password', 'ProfileController@updatePassword')->name('updatePassword');

        Route::get('/mail-box/mark-all-as-read', 'PmController@readAll')->name('mail-box.readAll');
        Route::resource('/mail-box', 'PmController');
        Route::get('/out-box', 'PmController@outbox')->name('outbox');
        Route::get('/mail-box/sendMail/{id}', 'PmController@sendMail')->name('mail-box.sendMail');
        Route::get('/mail-box/replay/{user_id}/{replay_id}', 'PmController@replayMail')->name('mail-box.replayMail');
        Route::patch('/mail-box/{mail_box}/read', 'PmController@read')->name('mail-box.read');

        Route::resource('shouts', 'ShoutController');

        Route::resource('/categories', 'CategoryController');

        Route::get('/uploads/deleted-files', 'UploadController@destroyedFiles')->name('uploads.history');
        Route::post('/uploads/deleted-files/restore-single/{id}', 'UploadController@restoreSingleDestroyedFile')->name('uploads.history.restore-single');
        Route::delete('/uploads/deleted-files/delete-single/{id}', 'UploadController@clearSingleDestroyedFile')->name('uploads.history.delete-single');
        Route::delete('/uploads/deleted-files/delete-all', 'UploadController@clearAllDestroyedFiles')->name('uploads.history.delete-all');

        Route::resource('/uploads', 'UploadController');
        Route::post('/imdb-data', 'UploadController@check_imdb')->name('check_imdb');


        Route::get('/approved-uploads', 'UploadController@allApproved')->name('approved-uploads');
        Route::get('/disapproved-uploads', 'UploadController@allDisapproved')->name('disapproved-uploads');
        Route::get('/pending-uploads', 'UploadController@allPending')->name('pending-uploads');

        Route::get('/my-approved-uploads', 'MyUploadController@allApproved')->name('my-approved-uploads');
        Route::get('/my-disapproved-uploads', 'MyUploadController@allDisapproved')->name('my-disapproved-uploads');
        Route::get('/my-pending-uploads', 'MyUploadController@allPending')->name('my-pending-uploads');


        Route::resource('/my-uploads', 'MyUploadController');
        Route::post('/my-imdb-data', 'MyUploadController@check_imdb')->name('my_check_imdb');

        Route::resource('/pins', 'PinController');

        Route::resource('/recommends', 'RecommendController');

        Route::resource('/upcoming', 'UpcomingController');

        Route::resource('/requests', 'RequestController');

        Route::patch('/requests/reset/{id}', 'RequestController@reset')->name('requests.reset');

        // Route::resource('/forum', 'ForumController');

        // Route::resource('/forum-topics', 'ForumTopicController');

        // Route::resource('/forum-topic-replies', 'ForumTopicReplyController');

    });


//Route::get('shouts/comments', 'App\ShoutController@index');

//Route::post('shouts/comment', 'App\ShoutController@store');


});

