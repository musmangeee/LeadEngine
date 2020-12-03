<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\UserCreated;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    protected $user;
    protected $setting;

    public function __construct(User $user, Setting $setting)
    {
        $this->user = $user;
        $this->setting = $setting;
    }

    public function store(Request $request)
    {
        $permissions = Permission::pluck('name')->toArray();

        $request->validate([
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'status' => 'required|in:active,disabled',
            'role' => 'required|exists:roles,name',
            'timezone' => 'required',
            'password' => 'required',
            'passwordConfirm' => 'required_with:password|same:password',
        ]);


        $selectedPermissions = explode(',', $request->selectedPermission);

        if (trim($request->selectedPermission) != '') {
            foreach ($selectedPermissions as $permission) {
                if (! in_array($permission, $permissions)) {
                    return response()->json([ 'message' => 'Permission not exist!' ], 422);
                }
            }
        }

        $photo = $request->file('photo');
        $photo_path = '';

        if ($photo) {
            $photo_path = Storage::disk('s3')->put('media/avatar', $photo);
        }

        //DB::beginTransaction(function() use($request, $photo_path) {
        $user = User::create([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'status' => $request->status,
                'password' => bcrypt($request->password),
                'photo_path' => $photo_path,
                'timezone' => $request->timezone
            ]);

        $info = json_decode($request->info);

        $user->information()->create([
                'job_title' => $info->jobTitle,
                'work_phone' => $info->workPhone,
                'ext' => $info->ext,
                'mobile_phone' => $info->mobilePhone,
            ]);



        $user->assignRole($request->role);
        if (trim($request->selectedPermission) != '') {
            $user->givePermissionTo($selectedPermissions);
        }
        //});


        $mail = Mail::to($user->email);
        $mail->send(new UserCreated($user, $request->password));

        return response()->json($user, 200);
    }

    public function show($id)
    {
        $user = User::with(['information','roles','permissions'])->find($id);

        $groupedPermissions = [];

        foreach ($user->permissions as $permission) {
            $explodedPermission = explode('.', $permission->name);
            if (isset($groupedPermissions[$explodedPermission[0]])) {
                $groupedPermissions[$explodedPermission[0]] = array_merge($groupedPermissions[$explodedPermission[0]], [$explodedPermission[1]]);
            } else {
                $groupedPermissions = array_add($groupedPermissions, $explodedPermission[0], [$explodedPermission[1]]);
            }
        }

        $user->my_permissions = $groupedPermissions;



        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
    
        $permissions = Permission::pluck('name')->toArray();

        $request->validate([
            'email' => 'required|unique:users,email,'.$user->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'status' => 'required|in:active,disabled',
            'role' => 'required|exists:roles,name',
            'timezone' => 'required',
            'password' => 'sometimes',
            'passwordConfirm' => 'sometimes|required_with:password|same:password',
        ]);

        $old_email = $user->email;
        $new_email = $request->email;

        if (trim($request->selectedPermission) != '') {
            $selectedPermissions = explode(',', $request->selectedPermission);
            foreach ($selectedPermissions as $permission) {
                if (!in_array($permission, $permissions)) {
                    return response()->json(['message' => 'Permission not exist!'], 422);
                }
            }
        }

        $photo = $request->file('photo');
        $photo_path = '';

        if ($photo) {
            $photo_path = Storage::disk('s3')->put('media/avatar', $photo);
        }


        //DB::beginTransaction(function() use($request, $photo_path) {
        $user->update([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'status' => $request->status,
                'photo_path' => $photo_path,
                'timezone' => $request->timezone
            ]);

        $info = json_decode($request->info);

        $user->information()->updateOrCreate(['user_id' => $user->id], [
                'job_title' => $info->jobTitle,
                'work_phone' => $info->workPhone,
                'ext' => $info->ext,
                'mobile_phone' => $info->mobilePhone,
            ]);

        $user->syncRoles([$request->role]);
        if (trim($request->selectedPermission) != '') {
            $user->syncPermissions($selectedPermissions);
        }

        //});

        if ($request->password) {
            $user->password = bcrypt($request->password);
            $user->save();
        }


        if ($old_email != $new_email) {
            $this->updateMapEmailToWebsite($old_email, $new_email);
        }


        $user->accessTokens()->delete();


        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }

    public function disable(Request $request)
    {
        if (!$user = User::find($request->id)->disable()) {
            return response()->json(['message' => 'Something wrong!'], 500);
        }

        return response()->json($user, 200);
    }

    public function activate(Request $request)
    {
        if (!$user = User::find($request->id)->activate()) {
            return response()->json(['message' => 'Something wrong!'], 500);
        }
        return response()->json($user, 200);
    }

    public function getRolesAndPermissions()
    {
        $user = auth('api')->user();

        $permissions = permissionToCaslRules($user->getAllPermissions());
        $roles = $user->getRoleNames();

        return response()->json([
            'permissions' => $permissions,
            'roles' => $roles
        ], 200);
    }

    public function getOptions()
    {
        $user = auth('api')->user();
        return response()->json([
            'options' => $user->options,
        ], 200);
    }

    public function getAll()
    {
        return User::where('status', 'active')->get();
    }

    public function list(Request $request)
    {
        if ($request->has('sort')) {
            list($sortCol, $sortDesc) = explode('|', request()->sort);
            ($sortDesc == 'true') ? $sortDir = 'desc' : $sortDir = 'asc';

            if ($sortCol == 'roles') {
                $query = User::with('roles')->orderBy($sortCol, $sortDir);
            } else {
                $query = User::with('roles')->orderBy($sortCol, $sortDir);
            }
        } else {
            $query = User::with('roles')->orderBy('id', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function ($q) use ($request) {
                $value = "%{$request->filter}%";
                $q->where('first_name', 'like', $value)
                  ->orWhere('last_name', 'like', $value)
                  ->orWhere('email', 'like', $value);
            });
        }


        $perPage = $request->has('size') ? (int) $request->size : null;

        return response()->json($query->paginate($perPage), 200);
    }

    public function profile(){
        $user = auth()->user();
        $permissions = permissionToCaslRules($user->getAllPermissions());
        $roles = $user->getRoleNames();
        $options = $user->options;
        $settings = $this->setting->pluck('value','name');
        return response()->json([
            'options' => $options,
            'roles' => $roles,
            'permissions' => $permissions,
            'settings' => $settings,
            'user' => $user
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        
        $user = auth()->user();

        $request->validate([
            'email' => 'required|unique:users,email,'.$user->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'password' => 'sometimes',
            'passwordConfirm' => 'sometimes|required_with:password|same:password',
            // 'status' => 'required'
        ]);


        $old_email = $user->email;
        $new_email = $request->email;



        $photo = $request->file('photo');
        $photo_path = '';

        if ($photo) {
            $photo_path = Storage::disk('s3')->put('media/avatar', $photo);
        }


        $user->update([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'photo_path' => $photo_path,
            'timezone' => $request->timezone,
            'status' => $request->user()->status
        ]);


        $info = json_decode($request->info);

        $user->information()->updateOrCreate(['user_id' => $user->id], [
            'job_title' => $info->jobTitle,
            'work_phone' => $info->workPhone,
            'ext' => $info->ext,
            'mobile_phone' => $info->mobilePhone,
        ]);


        if ($request->password) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        if ($old_email != $new_email) {
            $this->updateMapEmailToWebsite($old_email, $new_email);
        }

        return response()->json($user->load('options'), 200);
    }
}
