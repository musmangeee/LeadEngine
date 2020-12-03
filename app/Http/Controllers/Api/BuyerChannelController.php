<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BuyerChannel;
use App\Http\Requests\Api\CreateBuyerChannelRequest;
use App\Http\Requests\Api\UpdateBuyerChannelRequest;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class BuyerChannelController extends Controller
{
    protected $buyerChannel;

    public function __construct(BuyerChannel $buyerChannel)
    {
        $this->buyerChannel = $buyerChannel;
    }


    public function index(Request $request){
        $buyerChannels = $this->buyerChannel->with(['buyer']);
        if($request->has('buyer_id')){
            $buyerChannels->where('buyer_id',$request->get('buyer_id'));
        }
        $perPage = $request->has('perPage') ? (int) $request->perPage : 10;

        $results = $buyerChannels->paginate($perPage);
        return response()->json($results,200);
    }


    public function create(CreateBuyerChannelRequest $request){
        $validated = $request->validated();
        $validated['channel_key'] =  Uuid::uuid4();
        $buyerChannel = $this->buyerChannel->create(array_except($validated,'deliverySchedules'));

        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            foreach ($request->get('deliverySchedules') as $schedule) {
                $buyerChannel->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }

        return response()->json([
            'data' => $buyerChannel
        ]);
    }

    public function update(UpdateBuyerChannelRequest $request, $id){
        $validated = $request->validated();
        $buyerChannel = $this->buyerChannel->findOrFail($id);
        $buyerChannel->update(array_except($validated,'deliverySchedules'));

        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            $buyerChannel->deliverySchedules()->delete();
            foreach ($request->get('deliverySchedules') as $schedule) {
                $buyerChannel->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }
        else{
            $buyerChannel->deliverySchedules()->delete();
        }
        return response()->json([
            'data' => $buyerChannel
        ]);
    }

    public function show($id){
        $buyerChannel = $this->buyerChannel->with(['buyer','deliverySchedules'])->findOrFail($id);
        return response()->json([
            'data' => $buyerChannel
        ]);
    }

    public function delete($id){
        $buyerChannel = $this->buyerChannel->findOrFail($id);
        $buyerChannel->deliverySchedules()->delete();
        $buyerChannel->delete();

        return response()->json([
            'status' => 'success'
        ]);

    }

}
