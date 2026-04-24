@extends('layouts.admin')
@section('title', 'Edit Mission')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Mission</h1>
                    <p class="mt-2 text-sm text-gray-600">Update mission details and requirements</p>
                </div>
                <a href="{{ route('admin.missions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Missions
                </a>
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

        <!-- Edit Form -->
        <form method="POST" action="{{ route('admin.missions.update', $mission) }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Mission Information -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mission Information
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Mission Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required 
                                   value="{{ old('title', $mission->title) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition-colors"
                                   placeholder="Enter mission title">
                        </div>
                        
                        <div>
                            <label for="site_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Site <span class="text-red-500">*</span>
                            </label>
                            <select id="site_id" name="site_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition-colors">
                                <option value="">Select a site</option>
                                @foreach ($sites as $site)
                                    <option value="{{ $site->id }}" @selected(old('site_id', $mission->site_id) == $site->id)>
                                        {{ $site->name }} | {{ $site->city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule & Time -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Schedule & Time
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date" name="date" required 
                               value="{{ old('date', $mission->date) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition-colors">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="start_time" name="start_time" required 
                                   value="{{ old('start_time', $mission->start_time) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition-colors">
                        </div>
                        
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="end_time" name="end_time" required 
                                   value="{{ old('end_time', $mission->end_time) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Volunteer Requirements -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Volunteer Requirements
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="required_volunteers" class="block text-sm font-medium text-gray-700 mb-2">
                            Number of Volunteers Needed <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="required_volunteers" name="required_volunteers" min="1" required 
                               value="{{ old('required_volunteers', $mission->required_volunteers) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C1272D] focus:border-[#C1272D] transition-colors"
                               placeholder="Enter number of volunteers needed">
                        <p class="mt-2 text-sm text-gray-500">Specify how many volunteers are required for this mission</p>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-red-800">Please fix the following errors:</h3>
                            <div class="mt-3">
                                <ul class="list-disc list-inside space-y-2 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this mission?')) { window.location.href='{{ route('admin.missions.destroy', $mission) }}'; }" 
                            class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116 21H8a2 2 0 01-2-2V7m3 0V4a2 2 0 012-2h4a2 2 0 012 2v3m3 0H6"></path>
                        </svg>
                        Delete Mission
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.missions.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-[#C1272D] hover:bg-[#8B1A1F] transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Mission
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
