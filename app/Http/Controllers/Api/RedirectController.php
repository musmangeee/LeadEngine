<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    private $application;

    public function __construct(Application $application){
        $this->application = $application;
    }

    public function index(Request $request){
        $sortBy = $request->has('sortBy') ? $request->sortBy : 'id';
        $sortDesc = $request->sortDesc == 'true' ? 'desc' : 'asc';

        $applications = $this->application->with('status', 'provider', 'redirect')->orderBy($sortBy, $sortDesc);
        $perPage = $request->has('perPage') ? (int) $request->perPage : 10;

        $results = $applications->paginate($perPage);
        return response()->json($results,200);
    }

    public function getWidgetData(){
        $total_leads = Application::count();
        $total_leads_redirected = Application::has('redirect')->count();
        $total_leads_not_redirected = $total_leads - $total_leads_redirected;

        return [
            'totalApplication' => $total_leads,
            'totalRedirected' => $total_leads_redirected,
            'totalNotRedirected' => $total_leads_not_redirected
        ];
    }

}
