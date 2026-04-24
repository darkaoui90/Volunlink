@extends('layouts.admin')
@section('title', 'Missions')

@section('content')
<div x-data="{ tab: 'missions', showCreate: @js($errors->any()), selectedMission: null, city: '' }">
    @php
        $statusStyles = [
            'Upcoming' => 'bg-blue-100 text-blue-700',
            'Ongoing' => 'bg-green-100 text-green-700',
            'Completed' => 'bg-slate-100 text-slate-700',
        ];
    @endphp
    <!-- TABS -->
    <div class="flex border-b border-gray-200 mb-6">
        <button @click="tab='missions'"
                :class="tab==='missions' ? 'border-b-2 border-[#C1272D] text-[#C1272D]' : 'text-gray-500'"
                class="px-6 py-3 text-sm font-medium transition-colors">
            All missions
        </button>
        <button @click="tab='assignments'"
                :class="tab==='assignments' ? 'border-b-2 border-[#C1272D] text-[#C1272D]' : 'text-gray-500'"
                class="px-6 py-3 text-sm font-medium transition-colors">
            Assignments
        </button>
    </div>

    <!-- TAB 1 - MISSIONS -->
    <div x-show="tab==='missions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <!-- Top bar -->
        <div class="flex justify-between items-center mb-4">
            <form method="GET" class="flex gap-3 items-center">
                <input type="text" name="search" placeholder="Search missions" 
                       value="{{ request('search') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
                
                <select name="site" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
                    <option value="">All sites</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}" @selected((string) request('site') === (string) $site->id)>
                            {{ $site->name }} | {{ $site->city }}
                        </option>
                    @endforeach
                </select>
                
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
                    <option value="">All status</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                
                <button type="submit" class="bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    Filter
                </button>
            </form>
            
            <button @click="showCreate=true" class="bg-[#C1272D] text-white hover:bg-[#8B1A1F] rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                Create mission
            </button>
        </div>

        <!-- MISSIONS TABLE -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Title</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Site</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">City</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Date</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Time</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Type</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Slots</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Status</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($missions as $mission)
                        @php
                            $statusClass = $statusStyles[$mission->status_label] ?? $statusStyles['Upcoming'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-800">
                                <a href="{{ route('admin.missions.show', $mission) }}" class="text-[#C1272D] hover:text-[#8B1A1F] transition-colors">
                                    {{ $mission->title }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $mission->site_name }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $mission->site_city ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $mission->date }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $mission->start_time ?? '-' }} - {{ $mission->end_time ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $mission->site?->type ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="text-xs font-medium text-[#C1272D]">
                                    {{ $mission->volunteers->count() }}/{{ $mission->required_volunteers ?? 0 }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="{{ $statusClass }} text-xs px-2 py-1 rounded-full">{{ $mission->status_label }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.missions.show', $mission) }}" class="text-blue-400 hover:text-blue-600" title="View Mission">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.missions.assign', $mission) }}" class="text-green-400 hover:text-green-600" title="Assign Volunteers">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m3 0a9 9 0 11-18 0 9 9 0 0118 0zm-9 9a9 9 0 01-9-9"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.missions.edit', $mission) }}" class="text-gray-400 hover:text-gray-600" title="Edit Mission">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828L8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.missions.destroy', $mission) }}" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116 21H8a2 2 0 01-2-2V7a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2h8a2 2 0 002-2V7z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-gray-500">
                                No missions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($missions->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $missions->links() }}
            </div>
        @endif
    </div>

    <!-- TAB 2 - ASSIGNMENTS -->
    <div x-show="tab==='assignments'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Mission</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Date</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Assigned</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Needed</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Status</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($missions as $mission)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-800">
                                <a href="{{ route('admin.missions.show', $mission) }}" class="text-[#C1272D] hover:text-[#8B1A1F] transition-colors">
                                    {{ $mission->title }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $mission->date }}</td>
                            <td class="py-3 px-4">
                                <span class="text-xs font-medium text-[#C1272D]">
                                    {{ $mission->volunteers->count() }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-xs font-medium text-gray-600">
                                    {{ $mission->required_volunteers ?? 0 }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($mission->volunteers->count() >= ($mission->required_volunteers ?? 0))
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Fully Staffed</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Needs Volunteers</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.missions.assign', $mission) }}" class="text-green-400 hover:text-green-600" title="Assign Volunteers">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m3 0a9 9 0 11-18 0 9 9 0 0118 0zm-9 9a9 9 0 01-9-9"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.missions.show', $mission) }}" class="text-blue-400 hover:text-blue-600" title="View Mission">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                No missions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CREATE MISSION MODAL -->
    <div x-show="showCreate" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <form method="POST" action="{{ route('admin.missions.store') }}" class="space-y-4">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Create New Mission</h3>
                            <button type="button" @click="showCreate=false" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        @if ($sites->isEmpty())
                            <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                                Create at least one site before creating a mission.
                                <a href="{{ route('admin.sites.index') }}" class="font-semibold underline">Manage sites</a>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[#C1272D] focus:border-[#C1272D]">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" name="date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[#C1272D] focus:border-[#C1272D]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Site</label>
                                    <select name="site_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[#C1272D] focus:border-[#C1272D]">
                                        <option value="">Select a site</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}">{{ $site->name }} | {{ $site->city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                    <input type="time" name="start_time" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[#C1272D] focus:border-[#C1272D]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                    <input type="time" name="end_time" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[#C1272D] focus:border-[#C1272D]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Volunteers Needed</label>
                                <input type="number" name="required_volunteers" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[#C1272D] focus:border-[#C1272D]">
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="rounded-md border-red-300 bg-red-50 p-4">
                                <div class="text-sm text-red-600">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="showCreate=false" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" @disabled($sites->isEmpty()) class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#C1272D] hover:bg-[#8B1A1F] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C1272D] disabled:cursor-not-allowed disabled:bg-gray-300">
                                Create Mission
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
