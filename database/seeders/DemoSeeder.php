<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Incident;
use App\Models\Casualty;

class DemoSeeder extends Seeder {
    public function run(): void {
        $org = Organisation::create(['licence_number' => 'NMD-DEMO-001', 'org_name' => 'Niger Delta Petroleum Ltd', 'org_type' => 'operator', 'address' => 'Port Harcourt', 'lga' => 'Port Harcourt', 'state' => 'Rivers']);
        $admin = User::create(['name' => 'NMDPRA Inspector', 'email' => 'inspector@nmdpra.gov.ng', 'password' => bcrypt('password123'), 'role' => 'nmdpra_inspector', 'status' => 'active']);
        $operator = User::create(['name' => 'HSE Manager', 'email' => 'hse@nigerdelta.ng', 'password' => bcrypt('password123'), 'org_id' => $org->org_id, 'role' => 'operator_manager', 'status' => 'active']);
        
        $incidents = [
            ['type' => 'fire', 'severity' => 'fatal', 'lat' => 4.8156, 'lng' => 7.0498],
            ['type' => 'spill', 'severity' => 'critical', 'lat' => 5.0333, 'lng' => 7.9333],
            ['type' => 'vandalism', 'severity' => 'high', 'lat' => 6.3333, 'lng' => 5.6000],
        ];
        
        foreach ($incidents as $i => $data) {
            $incident = Incident::create(['reporter_id' => $operator->id, 'org_id' => $org->org_id, 'incident_type' => $data['type'], 'severity' => $data['severity'], 'description' => 'Demo ' . $data['type'] . ' incident in Niger Delta region.', 'location_address' => 'Niger Delta, ' . $data['type'] . ' site', 'latitude' => $data['lat'], 'longitude' => $data['lng'], 'occurred_at' => now()->subDays($i + 1)]);
            Casualty::create(['incident_id' => $incident->incident_id, 'fatalities_count' => $data['severity'] === 'fatal' ? 3 : 0, 'injuries_count' => $data['severity'] === 'fatal' ? 12 : 2, 'environmental_damage_est_usd' => 500000]);
        }
    }
}
