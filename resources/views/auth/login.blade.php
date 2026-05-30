@extends('layouts.app')
@section('title', 'Login - NMDPRA Tracker')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 mt-10">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-900">NMDPRA Incident Tracker</h2>
    @if($errors->any())<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login') }}">@csrf
        <div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label><input type="email" name="email" class="w-full px-3 py-2 border rounded-lg" required></div>
        <div class="mb-6"><label class="block text-gray-700 text-sm font-bold mb-2">Password</label><input type="password" name="password" class="w-full px-3 py-2 border rounded-lg" required></div>
        <button type="submit" class="w-full bg-blue-900 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-800">Sign In</button>
    </form>
</div>
@endsection
