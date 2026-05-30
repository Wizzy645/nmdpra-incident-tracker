@extends('layouts.app')
@section('title', 'Report Incident - NMDPRA')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-6 text-blue-900">Report New Incident</h2>
    <form method="POST" action="{{ route('incidents.store') }}" class="space-y-4">@csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-bold mb-1">Incident Type</label><select name="incident_type" class="w-full border rounded px-3 py-2" required><option value="">Select type...</option><option value="vandalism">Vandalism</option><option value="sabotage">Sabotage</option><option value="mechanical_failure">Mechanical Failure</option><option value="fire">Fire</option><option value="explosion">Explosion</option><option value="spill">Oil Spill</option><option value="theft">Theft</option><option value="other">Other</option></select></div>
            <div><label class="block text-sm font-bold mb-1">Severity</label><select name="severity" class="w-full border rounded px-3 py-2" required><option value="">Select severity...</option><option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option><option value="critical">Critical (24h)</option><option value="fatal">Fatal (24h)</option></select></div>
        </div>
        <div><label class="block text-sm font-bold mb-1">Description</label><textarea name="description" rows="4" class="w-full border rounded px-3 py-2" required minlength="20"></textarea></div>
        <div><label class="block text-sm font-bold mb-1">Location Address</label><input type="text" name="location_address" class="w-full border rounded px-3 py-2" required></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-sm font-bold mb-1">Latitude</label><input type="number" step="any" name="latitude" class="w-full border rounded px-3 py-2" placeholder="e.g., 4.8156"></div>
            <div><label class="block text-sm font-bold mb-1">Longitude</label><input type="number" step="any" name="longitude" class="w-full border rounded px-3 py-2" placeholder="e.g., 7.0498"></div>
        </div>
        <div><label class="block text-sm font-bold mb-1">Date/Time Occurred</label><input type="datetime-local" name="occurred_at" class="w-full border rounded px-3 py-2" required max="{{ now()->format('Y-m-d\TH:i') }}"></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><label class="block text-sm font-bold mb-1">Fatalities</label><input type="number" name="fatalities_count" class="w-full border rounded px-3 py-2" min="0" value="0"></div>
            <div><label class="block text-sm font-bold mb-1">Injuries</label><input type="number" name="injuries_count" class="w-full border rounded px-3 py-2" min="0" value="0"></div>
            <div><label class="block text-sm font-bold mb-1">Environmental Damage (USD)</label><input type="number" step="0.01" name="environmental_damage_est_usd" class="w-full border rounded px-3 py-2" min="0" value="0"></div>
        </div>
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">Submit Incident</button>
            <a href="{{ route('incidents.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
