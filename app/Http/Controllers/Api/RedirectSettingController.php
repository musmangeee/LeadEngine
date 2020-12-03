<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateRedirectSettingRequest;
use App\Http\Requests\Api\UpdateRedirectSettingRequest;
use App\Models\RedirectSetting;

class RedirectSettingController extends Controller
{
    private $redirectSetting;

    public function __construct(RedirectSetting $redirectSetting)
    {
        $this->redirectSetting = $redirectSetting;
        
    }

    public function index(){
        $redirectSettings = $this->redirectSetting->get();
        return response()->json([
            'data' => $redirectSettings
        ]);
    }

    public function create(CreateRedirectSettingRequest $request){
        $validated = $request->validated();
        $redirectSetting = $this->redirectSetting->create($validated);
        return response()->json([
            'data' => $redirectSetting
        ]);

    }

    public function update(UpdateRedirectSettingRequest $request, $id){
        $validated = $request->validated();
        $redirectSetting = $this->redirectSetting->findOrFail($id);
        $redirectSetting->update($validated);
        return response()->json([
            'data' => $redirectSetting
        ]);
    }

    public function show($id){
        $redirectSetting = $this->redirectSetting->findOrFail($id);
        return response()->json([
            'data' => $redirectSetting
        ]);
    }

    public function delete($id){
        $redirectSetting = $this->redirectSetting->findOrFail($id);
        $redirectSetting->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
