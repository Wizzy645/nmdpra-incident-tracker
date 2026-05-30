@extends('layouts.app')
@section('title', 'Operator Dashboard - NMDPRA')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-900">Operator Dashboard</h2>
    <p class="text-gray-600">{{ Auth::user()->organisation->org_name ?? 'Your Organisation' }}</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow"><div class="text-3xl font-bold text-blue-900">{{ $stats['total_incidents'] }}</div><div class="text-gray-600">Total Incidents</div></div>
    <div class="bg-white p-4 rounded-lg shadow"><div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div><div class="text-gray-600">Pending Reports</div></div>
</div>
<div class="bg-white rounded-lg shadow p-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Your Incidents</h3>
        <a href="{{ route('incidents.create') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Report New Incident</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="p-2 text-left">Ref</th><th class="p-2 text-left">Type</th><th class="p-2 text-left">Severity</th><th class="p-2 text-left">Status</th><th class="p-2 text-left">Deadline</th></tr></thead>
        <tbody>
            @foreach($incidents as $incident)
            <tr class="border-t">
                <td class="p-2 font-mono text-xs"><a href="{{ route('incidents.show', $incident) }}" class="text-blue-600 hover:underline">{{ $incident->incident_ref }}</a></td>
                <td class="p-2">{{ $incident->incident_type }}</td>
                <td class="p-2"><span class="px-2 py-1 rounded text-xs {{ $incident->severity === 'fatal' ? 'bg-red-100 text-red-800' : ($incident->severity === 'critical' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">{{ $incident->severity }}</span></td>
                <td class="p-2">{{ $incident->status }}</td>
                <td class="p-2 text-xs {{ $incident->is_compliant ? 'text-green-600' : 'text-red-600' }}">{{ $incident->compliance_deadline_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $incidents->links() }}
</div>
@endsection
