<?php
namespace App\Policies;
use App\Models\Incident;
use App\Models\User;

class IncidentPolicy {
    public function view(User $user, Incident $incident): bool {
        return in_array($user->role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor']) || $user->org_id === $incident->org_id;
    }
}
