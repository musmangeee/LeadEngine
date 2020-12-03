<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Setting;
use DateTime;
use DateTimeZone;
use PragmaRX\Countries\Package\Services\Countries;
use Illuminate\Support\Arr;

class HelperController extends Controller
{
    public function getTimezones()
    {
        $timezone_ids = DateTimeZone::listIdentifiers();
        $timezones = [];

        foreach ($timezone_ids as $timezone_id) {
            $tz = new DateTimeZone($timezone_id);
            $timezones = array_merge($timezones, [['id'=>$tz->getName(), 'offset'=>$tz->getOffset(new DateTime)]]);
        }

        $timezone_sorts = array();
        foreach ($timezones as $key => $row) {
            $offset[$key] = $row['offset'];
        }

        array_multisort($offset, SORT_ASC, $timezones);

        return response()->json($timezones, 200);
    }

    public function getLogoPath()
    {
        $logo_path = Setting::where('name', 'logo_path')->first();
        return response()->json($logo_path->value, 200);
    }

    public function getCountries(){
        $countries = new Countries();
	    $countries = Arr::sort($countries->all()->pluck('name.common'));
	    $new_value = $countries[235];
	    unset($countries[235]);
	    array_unshift($countries, $new_value);
	    return response()->json($countries);
    }

    public function getStatesByCountries($country){
        $countries = new Countries();
        $countries = $countries->where('name.common', $country)->first()->hydrateStates()->states->pluck('name', 'postal');
        return response()->json($countries);
    }
}
