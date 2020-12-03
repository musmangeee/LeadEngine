<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\CreateIntegrationRequest;
use App\Http\Requests\Api\UpdateIntegrationRequest;
use App\Models\Integration;
use Illuminate\Support\Facades\Schema;
use IlluminateAgnostic\Arr\Support\Arr;
use Ramsey\Uuid\Uuid;

class IntegrationController extends Controller
{
    private $integration;

    public function __construct(Integration $integration){
        $this->integration = $integration;
    }

    public function index(Request $request){
        $integrations = $this->integration->newQuery();
        $perPage = $request->has('perPage') ? (int) $request->perPage : 100;

        $results = $integrations->paginate($perPage);
        return response()->json($results,200);
    }

    public function create(CreateIntegrationRequest $request){
        $validated = $request->validated();

        if($request->post_content_type == 'xml'){
            libxml_use_internal_errors(true);

            $doc = simplexml_load_string($request->post_body);
            $xml = explode("\n", $request->post_body);

            if (!$doc) {
                $errors = libxml_get_errors();

                foreach ($errors as $error) {
                    return response()->json($error, 460);
                }

                libxml_clear_errors();
            }
        }

        if($request->post_content_type == 'json'){
            json_decode($request->post_body);
            if(!json_last_error() == JSON_ERROR_NONE){
                return response()->json('Content is not a valid JSON', 460);
            }else{
                if(empty(json_decode($request->post_body,true))){
                    return response()->json('JSON content is empty', 460);
                }
            }
        }

        if($request->post_content_type == 'form-data'){
            $validated['post_body'] = json_encode($validated['post_form_datas']);
        }

        unset($validated['post_form_datas']);

        $integration = $this->integration->create($validated);

        return response()->json([
            'data' => $integration
        ]);
    }

    public function update(UpdateIntegrationRequest $request, $id){
        $validated = $request->validated();
        $integration = $this->integration->findOrFail($id);

        if($request->post_content_type == 'xml'){
            libxml_use_internal_errors(true);

            $doc = simplexml_load_string($request->post_body);
            $xml = explode("\n", $request->post_body);

            if (!$doc) {
                $errors = libxml_get_errors();

                foreach ($errors as $error) {
                    return response()->json($error, 460);
                }

                libxml_clear_errors();
            }
        }

        if($request->post_content_type == 'json'){
            json_decode($request->post_body);
            if(!json_last_error() == JSON_ERROR_NONE){
                return response()->json('Content is not a valid JSON', 460);
            }else{
                if(empty(json_decode($request->post_body,true))){
                    return response()->json('JSON content is empty', 460);
                }
            }
        }

        if($request->post_content_type == 'form-data'){
            $validated['post_body'] = json_encode($validated['post_form_datas']);
        }

        unset($validated['post_form_datas']);

        $integration->update($validated);

        return response()->json([
            'data' => $integration
        ]);
    }

    public function show($id){
        $detailIntegration = $this->integration->findOrFail($id);
        return response()->json([
            'data' => $detailIntegration
        ]);
    }

    public function delete($id){
        $detailIntegration = $this->integration->findOrFail($id);
        $detailIntegration->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getApplicationColumns(){
        return Arr::sort(Schema::getColumnListing('applications'));
    }
}
