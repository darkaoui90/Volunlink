@extends('layouts.admin')
@section('title', 'Edit Volunteer')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Edit Volunteer</h2>
        <p class="text-gray-500">Update volunteer information</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.volunteers.update', $volunteer) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ $volunteer->name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ $volunteer->email }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="tel" name="phone" value="{{ $volunteer->phone ?? '' }}" placeholder="+212 6 12 34 56 78" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <select name="city" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
                    <option value="">Select city</option>
                    <option value="Casablanca" {{ ($volunteer->city ?? '') === 'Casablanca' ? 'selected' : '' }}>Casablanca</option>
                    <option value="Rabat" {{ ($volunteer->city ?? '') === 'Rabat' ? 'selected' : '' }}>Rabat</option>
                    <option value="Marrakech" {{ ($volunteer->city ?? '') === 'Marrakech' ? 'selected' : '' }}>Marrakech</option>
                    <option value="Agadir" {{ ($volunteer->city ?? '') === 'Agadir' ? 'selected' : '' }}>Agadir</option>
                    <option value="Fès" {{ ($volunteer->city ?? '') === 'Fès' ? 'selected' : '' }}>Fès</option>
                    <option value="Tangier" {{ ($volunteer->city ?? '') === 'Tangier' ? 'selected' : '' }}>Tangier</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Languages</label>
                <input type="text" name="languages" value="{{ $volunteer->languages ?? '' }}" placeholder="Arabic, English, French" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                <input type="text" name="skills" value="{{ $volunteer->skills ?? '' }}" placeholder="Translation, Customer Service, Medical" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                <select name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
                    <option value="Flexible" {{ ($volunteer->availability ?? 'Flexible') === 'Flexible' ? 'selected' : '' }}>Flexible</option>
                    <option value="Weekdays" {{ ($volunteer->availability ?? '') === 'Weekdays' ? 'selected' : '' }}>Weekdays only</option>
                    <option value="Weekends" {{ ($volunteer->availability ?? '') === 'Weekends' ? 'selected' : '' }}>Weekends only</option>
                    <option value="Evenings" {{ ($volunteer->availability ?? '') === 'Evenings' ? 'selected' : '' }}>Evenings only</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
                    <option value="volunteer" {{ $volunteer->role === 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                    <option value="coordinator" {{ $volunteer->role === 'coordinator' ? 'selected' : '' }}>Coordinator</option>
                    <option value="supervisor" {{ $volunteer->role === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="admin" {{ $volunteer->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-[#C1272D] text-white hover:bg-[#8B1A1F] rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    Update Volunteer
                </button>
                <a href="{{ route('admin.volunteers.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
