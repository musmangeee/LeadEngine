<?php

namespace App\Http\Controllers\Api;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\BuyerPlatChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBuyerPlatChannelRequest;
use App\Http\Requests\Api\UpdateBuyerPlatChannelRequest;

class BuyerPlatChannelController extends Controller
{
    private $buyerPlatChannel;

    public function __construct(BuyerPlatChannel $buyerPlatChannel)
    {
        $this->buyerPlatChannel = $buyerPlatChannel;
    }

    public function index(Request $request){
        $buyerPlatChannels = $this->buyerPlatChannel->with(['buyer']);
        if($request->has('buyer_id')){
            $buyerPlatChannels->where('buyer_id',$request->get('buyer_id'));
        }
        $perPage = $request->has('perPage') ? (int) $request->perPage : 100;

        $results = $buyerPlatChannels->paginate($perPage);
        return response()->json($results,200);
    }

    public function create(CreateBuyerPlatChannelRequest $request){
        $validated = $request->validated();
        $validated['channel_key'] =  Uuid::uuid4();
        $buyerPlatChannel = $this->buyerPlatChannel->create(array_except($validated,'deliverySchedules'));

        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            foreach ($request->get('deliverySchedules') as $schedule) {
                $buyerPlatChannel->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }

        return response()->json([
            'data' => $buyerPlatChannel
        ]);
    }

    public function update(UpdateBuyerPlatChannelRequest $request, $id){
        $validated = $request->validated();
        $buyerPlatChannel = $this->buyerPlatChannel->findOrFail($id);
        $buyerPlatChannel->update(array_except($validated,'deliverySchedules'));

        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            $buyerPlatChannel->deliverySchedules()->delete();
            foreach ($request->get('deliverySchedules') as $schedule) {
                $buyerPlatChannel->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }
        else {
            $buyerPlatChannel->deliverySchedules()->delete();
        }

        return response()->json([
            'data' => $buyerPlatChannel
        ]);
    }


    public function show($id){
        $buyerPlatChannel = $this->buyerPlatChannel->with(['buyer','deliverySchedules'])->findOrFail($id);
        return response()->json([
            'data' => $buyerPlatChannel
        ]);
    }

    public function delete($id){
        $buyerPlatChannel = $this->buyerPlatChannel->findOrFail($id);
        $buyerPlatChannel->deliverySchedules()->delete();
        $buyerPlatChannel->delete();

        return response()->json([
            'status' => 'success'
        ]);

    }

}
