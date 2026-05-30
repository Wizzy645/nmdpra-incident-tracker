@extends('layouts.app')
@section('title', 'Incidents - NMDPRA')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-blue-900">Incident Registry</h2>
    <a href="{{ route('incidents.create') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Report New Incident</a>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="p-3 text-left">Reference</th><th class="p-3 text-left">Organisation</th><th class="p-3 text-left">Type</th><th class="p-3 text-left">Severity</th><th class="p-3 text-left">Status</th><th class="p-3 text-left">Reported</th><th class="p-3 text-left">Compliant</th></tr></thead>
        <tbody>
            @foreach($incidents as $incident)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-mono text-xs"><a href="{{ route('incidents.show', $incident) }}" class="text-blue-600 hover:underline">{{ $incident->incident_ref }}</a></td>
                <td class="p-3">{{ $incident->organisation->org_name ?? 'N/A' }}</td>
                <td class="p-3">{{ $incident->incident_type }}</td>
                <td class="p-3"><span class="px-2 py-1 rounded text-xs {{ $incident->severity === 'fatal' ? 'bg-red-100 text-red-800' : ($incident->severity === 'critical' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">{{ $incident->severity }}</span></td>
                <td class="p-3">{{ $incident->status }}</td>
                <td class="p-3 text-xs">{{ $incident->reported_at->format('d M Y H:i') }}</td>
                <td class="p-3"><span class="px-2 py-1 rounded text-xs {{ $incident->is_compliant ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $incident->is_compliant ? 'YES' : 'NO' }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $incidents->links() }}
@endsection
