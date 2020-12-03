<?php

namespace App\Http\Controllers\Api;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\BuyerPandaChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBuyerPandaChannelRequest;
use App\Http\Requests\Api\UpdateBuyerPandaChannelRequest;

class BuyerPandaChannelController extends Controller
{
    private $buyerPandaChannel;

    public function __construct(BuyerPandaChannel $buyerPandaChannel)
    {
        $this->buyerPandaChannel = $buyerPandaChannel;
    }

    public function index(Request $request){
        $buyerPandaChannels = $this->buyerPandaChannel->with(['buyer']);
        if($request->has('buyer_id')){
            $buyerPandaChannels->where('buyer_id',$request->get('buyer_id'));
        }
        $perPage = $request->has('perPage') ? (int) $request->perPage : 100;

        $results = $buyerPandaChannels->paginate($perPage);
        return response()->json($results,200);
    }

    public function create(CreateBuyerPandaChannelRequest $request){
        $validated = $request->validated();
        $validated['channel_key'] =  Uuid::uuid4();
        if($validated['status']){
            $validated['status'] = 'active';
        }else{
            $validated['status'] = 'inactive';
        }
        $buyerPandaChannel = $this->buyerPandaChannel->create(array_except($validated,'deliverySchedules'));

        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            foreach ($request->get('deliverySchedules') as $schedule) {
                $buyerPandaChannel->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }

        return response()->json([
            'data' => $buyerPandaChannel
        ]);
    }

    public function update(UpdateBuyerPandaChannelRequest $request, $id){
        $validated = $request->validated();
        if($validated['status']){
            $validated['status'] = 'active';
        }else{
            $validated['status'] = 'inactive';
        }
        $buyerPandaChannel = $this->buyerPandaChannel->findOrFail($id);
        $buyerPandaChannel->update(array_except($validated,'deliverySchedules'));




        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            $buyerPandaChannel->deliverySchedules()->delete();
            foreach ($request->get('deliverySchedules') as $schedule) {
                $buyerPandaChannel->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }
        else{
            $buyerPandaChannel->deliverySchedules()->delete();
        }

        return response()->json([
            'data' => $buyerPandaChannel
        ]);
    }


    public function show($id){
        $buyerPandaChannel = $this->buyerPandaChannel->with(['buyer','deliverySchedules'])->findOrFail($id);
        if($buyerPandaChannel->status == 'active'){
            $buyerPandaChannel->status = true;
        }else{
            $buyerPandaChannel->status = false;
        }
        return response()->json([
            'data' => $buyerPandaChannel
        ]);
    }

    public function delete($id){
        $buyerPandaChannel = $this->buyerPandaChannel->findOrFail($id);
        $buyerPandaChannel->deliverySchedules()->delete();
        $buyerPandaChannel->delete();

        return response()->json([
            'status' => 'success'
        ]);

    }

}
