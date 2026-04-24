@extends('layouts.admin')
@section('title', 'Assign Volunteers')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Assign Volunteers</h1>
                    <p class="mt-2 text-sm text-gray-600">Select volunteers to assign to this mission</p>
                </div>
                <a href="{{ route('admin.missions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Missions
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-red-800 font-medium mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Mission Info Card -->
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
                    <div class="text-3xl font-bold">{{ $mission->volunteers->count() }}/{{ $mission->required_volunteers ?? 0 }}</div>
                    <div class="text-sm opacity-90">Volunteers Assigned</div>
                </div>
            </div>
        </div>

        <!-- Assignment Form -->
        <form method="POST" action="{{ route('admin.missions.assign.store', $mission) }}" class="space-y-6">
            @csrf
            
            <!-- Volunteers Selection -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Available Volunteers
                        <span class="text-sm font-normal text-gray-500">({{ $volunteers->count() }} total)</span>
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($volunteers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($volunteers as $volunteer)
                                <label class="relative flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="volunteers[]" 
                                           value="{{ $volunteer->id }}"
                                           {{ $mission->volunteers->contains($volunteer) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 rounded border-gray-300 text-[#C1272D] focus:ring-[#C1272D]">
                                    
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $volunteer->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $volunteer->email }}</div>
                                            </div>
                                            @if($mission->volunteers->contains($volunteer))
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-gray-500 mb-2">No volunteers available</p>
                            <p class="text-sm text-gray-400">Create volunteer accounts to enable assignment</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ $mission->volunteers->count() }}</span> volunteers currently assigned
                    <span class="mx-2">•</span>
                    <span class="font-medium">{{ $mission->required_volunteers ?? 0 }}</span> volunteers needed
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.missions.show', $mission) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-[#C1272D] hover:bg-[#8B1A1F] transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Update Assignments
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
