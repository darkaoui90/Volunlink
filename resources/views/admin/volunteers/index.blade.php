@extends('layouts.admin')
@section('title', 'Volunteers')

@section('content')
<!-- TOP BAR -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">All volunteers</h2>
        <p class="text-gray-500">{{ isset($volunteers) && method_exists($volunteers, 'total') ? $volunteers->total() : count($volunteers ?? []) }} registered</p>
    </div>
</div>

<!-- FILTER BAR -->
<div class="bg-white rounded-xl border border-gray-100 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <!-- Search input -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="search" placeholder="Search by name or email" 
                   value="{{ request('search') }}"
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
        </div>
        
        <!-- City select -->
        <select name="city" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
            <option value="">All cities</option>
            @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
            @endforeach
        </select>
        
        <!-- Skills select -->
        <select name="skills" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#C1272D] focus:border-transparent">
            <option value="">All skills</option>
            @foreach($skills as $skill)
                <option value="{{ $skill }}" {{ request('skills') == $skill ? 'selected' : '' }}>{{ $skill }}</option>
            @endforeach
        </select>
        
        <!-- Filter button -->
        <button type="submit" class="bg-[#C1272D] text-white hover:bg-[#8B1A1F] rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            Filter
        </button>
        
        <!-- Clear link -->
        @if(request()->hasAny(['search','city','skills']))
            <a href="{{ route('admin.volunteers.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Clear</a>
        @endif
    </form>
</div>

<!-- TABLE CARD -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div x-data="{ 
        selectedVolunteer: null, 
        showModal: false,
        getPrimaryAvailability(availability) {
            if(!availability) return 'Flexible';
            const first = availability.toLowerCase();
            if(first.includes('weekday')) return 'Weekdays';
            if(first.includes('weekend')) return 'Weekends';
            if(first.includes('full')) return 'Full-time';
            if(first.includes('on-demand') || first.includes('short notice')) return 'On-demand';
            return 'Flexible';
        }
    }">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">#</th>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Name</th>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Phone</th>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">City</th>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Languages</th>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Skills</th>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Availability</th>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Registered</th>
                    <th class="text-left py-3 px-4 text-xs uppercase text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($volunteers ?? [] as $vol)
                    @php
                        $colors = ['bg-red-100 text-[#C1272D]', 'bg-green-100 text-[#006233]', 'bg-amber-100 text-[#C9A84C]', 'bg-purple-100 text-purple-700', 'bg-blue-100 text-blue-700'];
                        $color = $colors[$loop->index % count($colors)];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-gray-600">{{ (isset($volunteers) && method_exists($volunteers, 'firstItem') ? $volunteers->firstItem() : 1) + $loop->index }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $color }} flex items-center justify-center text-xs font-semibold">
                                    {{ strtoupper(substr($vol->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $vol->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $vol->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $vol->phone ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $vol->city ?? 'Not specified' }}</td>
                        <td class="py-3 px-4 text-gray-600">
                            @if($vol->languages)
                                @php
                                    $languages = is_string($vol->languages) ? json_decode($vol->languages) : $vol->languages;
                                    $languages = is_array($languages) ? $languages : [];
                                @endphp
                                {{ implode(', ', array_slice($languages, 0, 2)) }}
                            @else
                                Not specified
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($vol->skills)
                                @php
                                    $skills = is_string($vol->skills) ? json_decode($vol->skills) : $vol->skills;
                                    $skills = is_array($skills) ? $skills : [];
                                @endphp
                                {{ implode(', ', array_slice($skills, 0, 2)) }}
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs rounded-full px-2 py-0.5">Not specified</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($vol->availability)
                                @php
                                    $availability = is_string($vol->availability) ? json_decode($vol->availability) : $vol->availability;
                                    $availability = is_array($availability) ? $availability : [];
                                    
                                    // Map various availability options to standard ones
                                    $primaryAvailability = 'Flexible';
                                    if(!empty($availability)) {
                                        $first = strtolower($availability[0]);
                                        if(strpos($first, 'weekday') !== false) {
                                            $primaryAvailability = 'Weekdays';
                                        } elseif(strpos($first, 'weekend') !== false) {
                                            $primaryAvailability = 'Weekends';
                                        } elseif(strpos($first, 'full') !== false) {
                                            $primaryAvailability = 'Full-time';
                                        } elseif(strpos($first, 'on-demand') !== false || strpos($first, 'short notice') !== false) {
                                            $primaryAvailability = 'On-demand';
                                        }
                                    }
                                @endphp
                                <span class="bg-blue-100 text-blue-700 text-xs rounded-full px-2 py-1">{{ $primaryAvailability }}</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs rounded-full px-2 py-1">Flexible</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-gray-400 text-xs">{{ isset($vol->created_at) && is_object($vol->created_at) ? $vol->created_at->diffForHumans() : 'Recently' }}</td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                @php $joinedDate = isset($vol->created_at) ? (is_string($vol->created_at) ? $vol->created_at : $vol->created_at->format('d M Y')) : 'N/A'; @endphp
                                <button @click="selectedVolunteer = {{ json_encode(['name'=>$vol->name, 'email'=>$vol->email, 'city'=>$vol->city ?? 'Not specified', 'phone'=>$vol->phone ?? 'N/A', 'languages'=>$vol->languages ?? 'Not specified', 'skills'=>$vol->skills ?? 'Not specified', 'availability'=>$vol->availability ?? 'Flexible', 'joined'=>$joinedDate]) }}; showModal = true" 
                                        class="text-xs text-[#C1272D] hover:underline">View</button>
                                <a href="{{ route('admin.volunteers.edit', $vol->id) }}" class="text-xs text-gray-600 hover:text-gray-800">Edit</a>
                                <form method="POST" action="{{ route('admin.volunteers.destroy', $vol->id) }}" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="py-16 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-gray-400 mt-3">No volunteers found</p>
                                <p class="text-gray-300 text-sm">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- VOLUNTEER VIEW MODAL -->
        <div x-show="showModal" x-transition 
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div @click.outside="showModal = false" 
                 class="bg-white rounded-2xl w-full max-w-lg mx-4 p-6">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-red-100 text-[#C1272D] flex items-center justify-center text-xl font-semibold" x-text="selectedVolunteer ? selectedVolunteer.name.substring(0,2).toUpperCase() : 'AA'">
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800" x-text="selectedVolunteer?.name"></h3>
                        <p class="text-gray-500" x-text="selectedVolunteer?.city"></p>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 pt-4 mb-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-400">Email</p>
                            <p class="font-medium" x-text="selectedVolunteer?.email"></p>
                        </div>
                        <div>
                            <p class="text-gray-400">Phone</p>
                            <p class="font-medium" x-text="selectedVolunteer?.phone"></p>
                        </div>
                        <div>
                            <p class="text-gray-400">Languages</p>
                            <p class="font-medium" x-text="selectedVolunteer?.languages ? JSON.parse(selectedVolunteer.languages).slice(0, 2).join(', ') : 'Not specified'"></p>
                        </div>
                        <div>
                            <p class="text-gray-400">Skills</p>
                            <p class="font-medium" x-text="selectedVolunteer?.skills ? JSON.parse(selectedVolunteer.skills).slice(0, 2).join(', ') : 'Not specified'"></p>
                        </div>
                        <div>
                            <p class="text-gray-400">Availability</p>
                            <div class="flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-700 text-xs rounded-full px-2 py-1" x-text="selectedVolunteer?.availability ? getPrimaryAvailability(JSON.parse(selectedVolunteer.availability)[0]) : 'Flexible'"></span>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-400">Joined</p>
                            <p class="font-medium" x-text="selectedVolunteer?.joined"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Assigned missions (static) -->
                <div class="border-t border-gray-100 pt-4 mb-4">
                    <h4 class="font-medium text-gray-800 mb-3">Assigned missions</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium">Stadium access - Gate A</p>
                                <p class="text-xs text-gray-500">June 18, 2030</p>
                            </div>
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Completed</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium">Fan zone welcome desk</p>
                                <p class="text-xs text-gray-500">June 18, 2030</p>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Upcoming</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium">VIP hospitality</p>
                                <p class="text-xs text-gray-500">June 19, 2030</p>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Upcoming</span>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance (static) -->
                <div class="border-t border-gray-100 pt-4 mb-4">
                    <h4 class="font-medium text-gray-800 mb-3">Attendance</h4>
                    <div class="flex gap-4">
                        <div class="bg-gray-100 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500">Missions</p>
                            <p class="text-sm font-semibold">14</p>
                        </div>
                        <div class="bg-green-100 text-green-700 rounded-lg px-3 py-2">
                            <p class="text-xs">Rate</p>
                            <p class="text-sm font-semibold">96%</p>
                        </div>
                    </div>
                </div>
                
                <!-- Close button -->
                <button @click="showModal = false" class="w-full bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg py-2 text-sm font-medium transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

        
    <!-- PAGINATION -->
    @if(isset($volunteers) && method_exists($volunteers, 'hasPages') && $volunteers->hasPages())
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-400">
                    Showing {{ $volunteers->firstItem() }} to {{ $volunteers->lastItem() }} of {{ $volunteers->total() }} volunteers
                </p>
                {{ $volunteers->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
