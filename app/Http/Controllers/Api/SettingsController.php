<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected $settings;

    public function __construct(Setting $settings)
    {
        $this->settings = $settings;
    }

    public function uploadLogo(Request $request)
    {
        $photo = $request->file('logo');
        $photo_path = '';

        if ($photo) {
            $photo_path = Storage::disk('s3')->put(app(Directory::class)->path().'media/logo', $photo);
        }

        $setting_logo = Setting::where('name', 'logo_path')->first();
        $setting_logo->value = $photo_path;
        $setting_logo->save();

        return response()->json($setting_logo, 200);
    }

    public function getSettings()
    {
        $settings = $this->settings->all();
        return response()->json($settings);
    }

    public function updateFakeLead(Request $request)
    {
        $setting_number_of_fake_lead_per_minute = Setting::firstOrNew(['name' => 'number_of_fake_lead_per_minute']);

        $setting_number_of_fake_lead_per_minute->value = $request->number_of_fake_lead_per_minute;
        $setting_number_of_fake_lead_per_minute->save();

        return response()->json('Success', 200);
    }

    public function deleteAllFakeLead()
    {
        return Application::where('is_fake', true)->delete();
    }
}
