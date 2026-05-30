<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Casualty;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller {
    public function index() {
        $user = Auth::user();
        $query = Incident::with(['reporter', 'organisation', 'casualty']);
        if (!in_array($user->role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor'])) {
            $query->where('org_id', $user->org_id);
        }
        $incidents = $query->latest('reported_at')->paginate(15);
        return view('incidents.index', compact('incidents'));
    }

    public function create() {
        $user = Auth::user();
        // Only operators and field agents can report incidents
        if (in_array($user->role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor'])) {
            return redirect()->route('incidents.index')->with('error', 'Administrators cannot report incidents. Use an operator account.');
        }
        if (!$user->org_id) {
            return redirect()->route('incidents.index')->with('error', 'Your account is not linked to any organisation.');
        }
        return view('incidents.create');
    }

    public function store(Request $request) {
        $user = Auth::user();
        
        // Block admin users from creating incidents
        if (in_array($user->role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor'])) {
            return redirect()->route('incidents.index')->with('error', 'Administrators cannot report incidents.');
        }
        
        if (!$user->org_id) {
            return redirect()->route('incidents.index')->with('error', 'Your account is not linked to any organisation.');
        }

        $validated = $request->validate([
            'incident_type' => 'required|in:vandalism,sabotage,mechanical_failure,fire,explosion,spill,theft,other',
            'severity' => 'required|in:low,medium,high,critical,fatal',
            'description' => 'required|string|min:20',
            'location_address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'occurred_at' => 'required|date|before_or_equal:now',
            'fatalities_count' => 'nullable|integer|min:0',
            'injuries_count' => 'nullable|integer|min:0',
            'environmental_damage_est_usd' => 'nullable|numeric|min:0',
        ]);

        $incident = Incident::create([
            'reporter_id' => $user->id,
            'org_id' => $user->org_id,
            'incident_type' => $validated['incident_type'],
            'severity' => $validated['severity'],
            'description' => $validated['description'],
            'location_address' => $validated['location_address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'occurred_at' => $validated['occurred_at'],
        ]);

        Casualty::create([
            'incident_id' => $incident->incident_id,
            'fatalities_count' => $validated['fatalities_count'] ?? 0,
            'injuries_count' => $validated['injuries_count'] ?? 0,
            'environmental_damage_est_usd' => $validated['environmental_damage_est_usd'] ?? 0,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'incident_id' => $incident->incident_id,
            'action_type' => 'create',
            'action_description' => 'Incident reported: ' . $incident->incident_ref,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('incidents.index')->with('success', 'Incident ' . $incident->incident_ref . ' reported successfully.');
    }

    public function show(Incident $incident) {
        // Simple authorization without Gate policy
        $user = Auth::user();
        if (!in_array($user->role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor']) && $user->org_id !== $incident->org_id) {
            abort(403, 'You do not have permission to view this incident.');
        }
        return view('incidents.show', compact('incident'));
    }
}
