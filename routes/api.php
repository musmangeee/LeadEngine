<?php

use Illuminate\Http\Request;

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

//include all routes created in /routes/api, however currently it didn't play well with testing
Route::group(['middleware' => 'auth:api'], function () {
    foreach (File::allFiles(__DIR__ . '/api') as $partial) {
        require_once $partial->getPathname();
    }
});


// admin api endpoint, moved here because from require_once declaration below because require_once didn't play well with api-testing
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/leads', 'Api\LeadController@index');
    Route::get('/leads/daily-lead', 'Api\LeadController@dailyLead');
    Route::get('/leads/stat', 'Api\LeadController@stats');
    Route::get('/leads/status', 'Api\LeadController@postList');
    Route::get('/leads/status/{id}', 'Api\LeadController@postDetail');
    Route::get('/leads/failed-applications', 'Api\LeadController@failedApplications');
    Route::get('/leads/failed/{id}', 'Api\LeadController@showFailedApplication');
    Route::get('/leads/{id}', 'Api\LeadController@show');
    Route::put('/leads/{id}', 'Api\LeadController@update');
    Route::delete('/leads/{id}', 'Api\LeadController@delete');

    Route::get('/address-ban', 'Api\AddressBanController@index');
    Route::post('/address-ban', 'Api\AddressBanController@create');
    Route::post('/address-ban/check-unique-address', 'Api\AddressBanController@checkUniqueAddressBan');
    Route::get('/address-ban/{id}', 'Api\AddressBanController@show');
    Route::put('/address-ban/{id}', 'Api\AddressBanController@update');
    Route::delete('/address-ban/{id}', 'Api\AddressBanController@delete');

    Route::get('/portfolios', 'Api\PortfolioController@index');
    Route::post('/portfolios', 'Api\PortfolioController@create');
    Route::get('/portfolios/routings', 'Api\PortfolioController@routingList');
    Route::get('/portfolios/{id}', 'Api\PortfolioController@show');
    Route::put('/portfolios/{id}', 'Api\PortfolioController@update');
    Route::delete('/portfolios/{id}', 'Api\PortfolioController@delete');
    Route::post('/portfolios/daily-stats', 'Api\PortfolioController@dailyStats');

    Route::get('/providers', 'Api\ProviderController@index');
    Route::post('/providers', 'Api\ProviderController@create');
    Route::get('/providers/{id}', 'Api\ProviderController@show');
    Route::put('/providers/{id}', 'Api\ProviderController@update');
    Route::delete('/providers/{id}', 'Api\ProviderController@delete');
    Route::post('providers/daily-stats', 'Api\ProviderController@dailyStats');

    Route::get('/integrations/get-application-columns', 'Api\IntegrationController@getApplicationColumns');
    Route::get('/integrations', 'Api\IntegrationController@index');
    Route::post('/integrations', 'Api\IntegrationController@create');
    Route::get('/integrations/{id}', 'Api\IntegrationController@show');
    Route::put('/integrations/{id}', 'Api\IntegrationController@update');
    Route::delete('/integrations/{id}', 'Api\IntegrationController@delete');


    Route::get('/redirects', 'Api\RedirectController@index');
    Route::get('/redirects/widget-data', 'Api\RedirectController@getWidgetData');

    Route::get('/buyers', 'Api\BuyerController@index');
    Route::post('/buyers', 'Api\BuyerController@create');
    Route::get('/buyers/{id}', 'Api\BuyerController@show');
    Route::put('/buyers/{id}', 'Api\BuyerController@update');
    Route::delete('/buyers/{id}', 'Api\BuyerController@delete');
    Route::post('buyers/daily-stats', 'Api\BuyerController@dailyStats');
    Route::post('buyers/daily-stats-export', 'Api\BuyerController@countByBuyerStatsExport');
    Route::post('buyers/daily-stats-send-email', 'Api\BuyerController@countByBuyerStatsSendEmail');

    Route::get('/buyer-channels', 'Api\BuyerChannelController@index');
    Route::post('/buyer-channels', 'Api\BuyerChannelController@create');
    Route::get('/buyer-channels/{id}', 'Api\BuyerChannelController@show');
    Route::put('/buyer-channels/{id}', 'Api\BuyerChannelController@update');
    Route::delete('/buyer-channels/{id}', 'Api\BuyerChannelController@delete');

    Route::get('/buyer-panda-channels', 'Api\BuyerPandaChannelController@index');
    Route::post('/buyer-panda-channels', 'Api\BuyerPandaChannelController@create');
    Route::get('/buyer-panda-channels/{id}', 'Api\BuyerPandaChannelController@show');
    Route::put('/buyer-panda-channels/{id}', 'Api\BuyerPandaChannelController@update');
    Route::delete('/buyer-panda-channels/{id}', 'Api\BuyerPandaChannelController@delete');

    Route::get('/buyer-plat-channels', 'Api\BuyerPlatChannelController@index');
    Route::post('/buyer-plat-channels', 'Api\BuyerPlatChannelController@create');
    Route::get('/buyer-plat-channels/{id}', 'Api\BuyerPlatChannelController@show');
    Route::put('/buyer-plat-channels/{id}', 'Api\BuyerPlatChannelController@update');
    Route::delete('/buyer-plat-channels/{id}', 'Api\BuyerPlatChannelController@delete');

    Route::get('/redirect-settings', 'Api\RedirectSettingController@index');
    Route::post('/redirect-settings', 'Api\RedirectSettingController@create');
    Route::get('/redirect-settings/{id}', 'Api\RedirectSettingController@show');
    Route::put('/redirect-settings/{id}', 'Api\RedirectSettingController@update');
    Route::delete('/redirect-settings/{id}', 'Api\RedirectSettingController@delete');

    Route::get('/settings', 'Api\SettingsController@getSettings');
    Route::put('/settings/fake-lead', 'Api\SettingsController@updateFakeLead');
    Route::delete('/settings/delete-all-fake-lead', 'Api\SettingsController@deleteAllFakeLead');

});


//Auth API
Route::post('/password-reset', 'Api\LoginController@passwordReset');
Route::post('/password-change', 'Api\LoginController@passwordChange');

Route::middleware('auth:api')->get('/me', function (Request $request) {
    $settings = \App\Setting::pluck('value', 'name');
    $user = \App\User::with('information', 'options')->find($request->user()->id);
    return response()->json([
        'user' => $user,
        'settings' => $settings,
    ], 200);
});

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

//Public API routes
Route::get('/helpers/get-timezones', 'Api\HelperController@getTimezones');
Route::get('/helpers/get-logo-path', 'Api\HelperController@getLogoPath');
Route::get('/helpers/countries', 'Api\HelperController@getCountries');
Route::get('/helpers/states/{country}', 'Api\HelperController@getStatesByCountries');

//Lead Intake
Route::post('/leadintake', 'Api\LeadController@intake');
Route::get('/list', 'Api\LeadController@list');
