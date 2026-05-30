<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Organisation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {
    public function admin() {
        $stats = [
            'total_incidents' => Incident::count(),
            'fatal_incidents' => Incident::where('severity', 'fatal')->count(),
            'pending_investigation' => Incident::where('status', 'under_investigation')->count(),
            'non_compliant' => Incident::where('is_compliant', false)->count(),
        ];
        $recent_incidents = Incident::with(['reporter', 'organisation'])->latest('reported_at')->take(10)->get();
        $incidents_by_state = Organisation::select('state', DB::raw('count(*) as count'))->join('incidents', 'organisations.org_id', '=', 'incidents.org_id')->groupBy('state')->get();
        return view('dashboard.admin', compact('stats', 'recent_incidents', 'incidents_by_state'));
    }
    public function operator() {
        $user = Auth::user();
        $stats = ['total_incidents' => Incident::where('org_id', $user->org_id)->count(), 'pending' => Incident::where('org_id', $user->org_id)->where('status', 'reported')->count()];
        $incidents = Incident::where('org_id', $user->org_id)->latest('reported_at')->paginate(10);
        return view('dashboard.operator', compact('stats', 'incidents'));
    }
}
