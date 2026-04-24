@extends('layouts.admin')
@section('title', 'Create Mission')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Create Mission</h1>
                    <a href="{{ route('admin.missions.index') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
                
                <form method="POST" action="{{ route('admin.missions.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Mission Details -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Mission Details</h2>

                        @if ($sites->isEmpty())
                            <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                                Create at least one site before creating a mission.
                                <a href="{{ route('admin.sites.index') }}" class="font-semibold underline">Manage sites</a>
                            </div>
                        @endif
                         
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Mission Title</label>
                                <input type="text" id="title" name="title" required 
                                       value="{{ old('title') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C1272D] focus:ring-[#C1272D]">
                            </div>
                            
                            <div>
                                <label for="site_id" class="block text-sm font-medium text-gray-700 mb-1">Site</label>
                                <select id="site_id" name="site_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C1272D] focus:ring-[#C1272D]">
                                    <option value="">Select a site</option>
                                    @foreach ($sites as $site)
                                        <option value="{{ $site->id }}" @selected(old('site_id') == $site->id)>
                                            {{ $site->name }} | {{ $site->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Schedule</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                <input type="date" id="date" name="date" required 
                                       value="{{ old('date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C1272D] focus:ring-[#C1272D]">
                            </div>
                            
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                <input type="time" id="start_time" name="start_time" required 
                                       value="{{ old('start_time') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C1272D] focus:ring-[#C1272D]">
                            </div>
                            
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                <input type="time" id="end_time" name="end_time" required 
                                       value="{{ old('end_time') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C1272D] focus:ring-[#C1272D]">
                            </div>
                        </div>
                    </div>

                    <!-- Volunteer Requirements -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Volunteer Requirements</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="required_volunteers" class="block text-sm font-medium text-gray-700 mb-1">Number of Volunteers Needed</label>
                                <input type="number" id="required_volunteers" name="required_volunteers" min="1" required 
                                       value="{{ old('required_volunteers', 1) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C1272D] focus:ring-[#C1272D]">
                            </div>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="rounded-md border-red-300 bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.missions.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" @disabled($sites->isEmpty()) class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#C1272D] hover:bg-[#8B1A1F] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C1272D] transition-colors disabled:cursor-not-allowed disabled:bg-gray-300">
                            Create Mission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
