<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckUniqueAddressBanRequest;
use App\Http\Requests\Api\CreateAddressBanRequest;
use App\Http\Requests\Api\UpdateAddressBanRequest;
use App\Models\AddressBan;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class AddressBanController extends Controller
{
    protected $addressBan;
    
    public function __construct(AddressBan $addressBan)
    {
        $this->addressBan = $addressBan;
    }

    public function index(){
        $addressBans = $this->addressBan->get();
        return response()->json([
            'data' => $addressBans
        ]);
    }

    public function checkUniqueAddressBan(CheckUniqueAddressBanRequest $request, MessageBag $errors){
        $validated = $request->validated();
        if($validated['type'] == AddressBan::TYPE_HOSTNAME){
            $hostname = $validated['address'];

            $exAddressBans = $this->addressBan->where('hostname',$hostname);
            if(!empty($validated['address_ban_id'])){
                $exAddressBans = $exAddressBans->get()->except($validated['address_ban_id']);
            }
            $exAddressBanCnt = $exAddressBans->count();
            if($exAddressBanCnt){
                $errors->add('address','Data already exists.');
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $errors->toArray()
                ],422);
            }
        }
        elseif ($validated['type'] == AddressBan::TYPE_IP_ADDRESS){
            $ipAddress = $validated['address'];

            $exAddressBans = $this->addressBan->where('ip_address',$ipAddress);
            if(!empty($validated['address_ban_id'])){
                $exAddressBans = $exAddressBans->get()->except($validated['address_ban_id']);
            }
            $exAddressBanCnt = $exAddressBans->count();

            if($exAddressBanCnt){
                $errors->add('address','Data already exists.');
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $errors->toArray()
                ],422);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function create(CreateAddressBanRequest $request, MessageBag $errors){
        $validated = $request->validated();
        $hostname = null;
        $ipAddress = null;
        if($validated['type'] == AddressBan::TYPE_HOSTNAME){
            $hostname = $validated['address'];

            $exAddressBanCnt = $this->addressBan->where('hostname',$hostname)->count();
            if($exAddressBanCnt){
                $errors->add('address','Data already exists.');
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $errors->toArray()
                ],422);
            }
        }
        elseif ($validated['type'] == AddressBan::TYPE_IP_ADDRESS){
            $ipAddress = $validated['address'];

            $exAddressBanCnt = $this->addressBan->where('ip_address',$ipAddress)->count();
            if($exAddressBanCnt){
                $errors->add('address','Data already exists.');
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $errors->toArray()
                ],422);
            }
        }

        $addressBan = $this->addressBan->create([
            'hostname' => $hostname,
            'ip_address' => $ipAddress
        ]);

        return response()->json([
            'data' => $addressBan
        ]);
    }

    public function show($id){
        $addressBan = $this->addressBan->findOrFail($id);
        return response()->json([
            'data' => $addressBan
        ]);
    }

    public function update($id, UpdateAddressBanRequest $request){
        $validated = $request->validated();
        $addressBan = $this->addressBan->findOrFail($id);

        if($validated['type'] == AddressBan::TYPE_HOSTNAME){
            $hostname = $validated['address'];
            $ipAddress = null;
        }
        elseif ($validated['type'] == AddressBan::TYPE_IP_ADDRESS){
            $ipAddress = $validated['address'];
            $hostname = null;
        }

        $addressBan->update([
            'hostname' => $hostname,
            'ip_address' => $ipAddress
        ]);

        return response()->json([
            'data' => $addressBan
        ]);
    }

    public function delete($id){
        $addressBan = $this->addressBan->findOrFail($id);
        $addressBan->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
