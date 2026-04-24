@extends('layouts.admin')
@section('title', 'Mission Details')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mission Details</h1>
                    <p class="mt-2 text-sm text-gray-600">View mission information and requirements</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.missions.edit', $mission) }}" class="inline-flex items-center px-4 py-2 border border-[#C1272D] rounded-lg text-sm font-medium text-[#C1272D] bg-white hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Mission
                    </a>
                    <a href="{{ route('admin.missions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Missions
                    </a>
                </div>
            </div>
        </div>

        <!-- Mission Preview Card -->
        <div class="bg-gradient-to-r from-[#C1272D] to-[#8B1A1F] rounded-xl p-6 mb-8 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-semibold mb-2">{{ $mission->title }}</h2>
                    <div class="flex items-center gap-4 text-sm opacity-90">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $mission->display_location }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $mission->date }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $mission->start_time ?? 'Not set' }} - {{ $mission->end_time ?? 'Not set' }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $mission->required_volunteers ?? 0 }}</div>
                    <div class="text-sm opacity-90">Volunteers Needed</div>
                </div>
            </div>
        </div>

        <!-- Mission Information Display -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mission Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Mission Title</div>
                        <div class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                            {{ $mission->title }}
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Location</div>
                        <div class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                            {{ $mission->display_location }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule & Time Display -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Schedule & Time
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">Date</div>
                    <div class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                        {{ $mission->date }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">Start Time</div>
                        <div class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                            {{ $mission->start_time ?? 'Not set' }}
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-500 mb-1">End Time</div>
                        <div class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                            {{ $mission->end_time ?? 'Not set' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Volunteer Requirements Display -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Volunteer Requirements
                </h3>
            </div>
            <div class="p-6">
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">Number of Volunteers Needed</div>
                    <div class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                        {{ $mission->required_volunteers ?? 0 }}
                    </div>
                    <p class="mt-2 text-sm text-gray-500">This mission requires {{ $mission->required_volunteers ?? 0 }} volunteers to be successfully completed.</p>
                </div>
            </div>
        </div>

        <!-- Mission Status -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mission Status
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full font-medium">
                        Upcoming
                    </span>
                    <div class="text-sm text-gray-600">
                        Mission is scheduled and ready for volunteer assignment
                    </div>
                </div>
            </div>
        </div>

        <!-- Volunteer Assignments -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Volunteer Assignments
                </h3>
            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">No volunteers assigned yet.</p>
                    <p class="text-sm text-gray-400 mb-4">Volunteer assignment feature will be available in the next phase.</p>
                    <div class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 rounded-lg text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Coming Soon
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-8 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('admin.missions.destroy', $mission) }}" onsubmit="return confirm('Are you sure you want to delete this mission? This action cannot be undone.')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116 21H8a2 2 0 01-2-2V7m3 0V4a2 2 0 012-2h4a2 2 0 012 2v3m3 0H6"></path>
                        </svg>
                        Delete Mission
                    </button>
                </form>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.missions.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Back to Missions
                </a>
                <a href="{{ route('admin.missions.edit', $mission) }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-[#C1272D] hover:bg-[#8B1A1F] transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Mission
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
