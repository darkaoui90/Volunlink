@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 xl:grid-cols-4">
    <!-- Total Volunteers with World Cup -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#C1272D] to-[#8E1C20] p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
        <div class="relative z-10">
            <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-red-100">Total volunteers</p>
            <p class="text-4xl font-bold text-white">{{ number_format($totalVolunteers) }}</p>
            <div class="mt-4 flex items-center gap-2 rounded-full bg-black/20 px-3 py-1 w-fit backdrop-blur-sm">
                <svg class="h-4 w-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <p class="text-xs font-medium text-red-50">{{ number_format($volunteerSignupsThisMonth) }} joined this month</p>
            </div>
        </div>
        <!-- 3D World Cup Icon -->
        <img src="{{ asset('images/world_cup_icon.png') }}" class="absolute -bottom-4 -right-4 h-32 w-32 object-contain transition-transform duration-500 group-hover:scale-110" alt="World Cup">
        <div class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-white opacity-5 blur-2xl"></div>
    </div>

    <!-- Active Missions -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#006233] to-[#004724] p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
        <div class="relative z-10">
            <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-green-100">Active missions</p>
            <p class="text-4xl font-bold text-white">{{ number_format($totalMissions) }}</p>
            <div class="mt-4 flex items-center gap-2 rounded-full bg-black/20 px-3 py-1 w-fit backdrop-blur-sm">
                <svg class="h-4 w-4 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs font-medium text-green-50">{{ $totalMissions > 0 ? $missionFillRate.'% staffing fill' : 'No missions yet' }}</p>
            </div>
        </div>
        <!-- 3D Missions Icon -->
        <img src="{{ asset('images/active_missions_icon.png') }}" class="absolute -bottom-4 -right-4 h-32 w-32 object-contain transition-transform duration-500 group-hover:scale-110" alt="Missions">
        <div class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-white opacity-5 blur-2xl"></div>
    </div>

    <!-- Attendance Rate -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#C9A84C] to-[#9A8139] p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
        <div class="relative z-10">
            <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-yellow-100">Attendance rate</p>
            <p class="text-4xl font-bold text-white">{{ $attendanceRate }}%</p>
            <div class="mt-4 flex items-center gap-2 rounded-full bg-black/20 px-3 py-1 w-fit backdrop-blur-sm">
                <svg class="h-4 w-4 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs font-medium text-yellow-50">{{ number_format($recordedAttendanceCount) }} recorded {{ $recordedAttendanceCount === 1 ? 'entry' : 'entries' }}</p>
            </div>
        </div>
        <!-- 3D Attendance Icon -->
        <img src="{{ asset('images/attendance_icon.png') }}" class="absolute -bottom-4 -right-4 h-32 w-32 object-contain transition-transform duration-500 group-hover:scale-110" alt="Attendance">
        <div class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-white opacity-10 blur-2xl"></div>
    </div>

    <!-- Cities Covered -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
        <div class="relative z-10">
            <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-300">Cities covered</p>
            <p class="text-4xl font-bold text-white">{{ number_format($citiesCount) }}</p>
            <div class="mt-4 flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 w-fit backdrop-blur-sm">
                <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                </svg>
                <p class="text-xs font-medium text-slate-300">{{ $citiesCount > 0 ? 'Distinct cities' : 'No city data yet' }}</p>
            </div>
        </div>
        <!-- 3D Cities Icon -->
        <img src="{{ asset('images/cities_icon.png') }}" class="absolute -bottom-4 -right-2 h-32 w-32 object-contain transition-transform duration-500 group-hover:scale-110" alt="Cities">
        <div class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-white opacity-5 blur-2xl"></div>
    </div>
</div>

<div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="xl:col-span-2">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent volunteer registrations</h3>
                <a href="{{ route('admin.volunteers.index') }}" class="text-sm text-[#C1272D] hover:underline">View all &rarr;</a>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">City</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Languages</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Skills</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentVolunteers as $vol)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-xs font-semibold text-[#C1272D]">
                                        {{ strtoupper(substr($vol->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $vol->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $vol->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $vol->city ?: 'Not specified' }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($vol->languages)
                                    @php
                                        $decodedLanguages = is_string($vol->languages) ? json_decode($vol->languages, true) : $vol->languages;
                                        $languages = is_array($decodedLanguages)
                                            ? $decodedLanguages
                                            : array_values(array_filter(array_map('trim', explode(',', (string) $vol->languages))));
                                    @endphp
                                    {{ implode(', ', array_slice($languages, 0, 2)) ?: 'Not specified' }}
                                @else
                                    Not specified
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($vol->skills)
                                    @php
                                        $decodedSkills = is_string($vol->skills) ? json_decode($vol->skills, true) : $vol->skills;
                                        $skills = is_array($decodedSkills)
                                            ? $decodedSkills
                                            : array_values(array_filter(array_map('trim', explode(',', (string) $vol->skills))));
                                    @endphp
                                    {{ implode(', ', array_slice($skills, 0, 2)) ?: 'Not specified' }}
                                @else
                                    Not specified
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $vol->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <p class="text-sm text-gray-400">No volunteers yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4 xl:col-span-1">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Volunteers by city</h3>

            @php
                $maxCity = $volunteersByCity->max('total') ?: 1;
            @endphp

            @forelse($volunteersByCity as $item)
                <div class="flex items-center gap-3 py-2">
                    <span class="w-24 truncate text-sm text-gray-700">{{ $item['city'] }}</span>
                    <div class="h-1.5 flex-1 rounded-full bg-gray-100">
                        <div class="h-1.5 rounded-full bg-[#C1272D]" style="width: {{ round($item['total'] / $maxCity * 100) }}%"></div>
                    </div>
                    <span class="w-10 text-right text-xs text-gray-400">{{ number_format($item['total']) }}</span>
                </div>
            @empty
                <p class="py-8 text-sm text-gray-400">No volunteer city data yet.</p>
            @endforelse
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Mission coverage</h3>

            @php
                $covered = $missionCoverage['covered'];
                $partial = $missionCoverage['partial'];
                $uncovered = $missionCoverage['uncovered'];
                $totalCoverage = $covered + $partial + $uncovered;
            @endphp

            @if($totalCoverage === 0)
                <p class="py-8 text-sm text-gray-400">No missions available yet.</p>
            @else
                <div class="space-y-3">
                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <span class="text-sm text-gray-700">Fully covered</span>
                            <span class="text-sm font-semibold text-[#006233]">{{ $covered }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-[#006233]" style="width: {{ round($covered / $totalCoverage * 100) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <span class="text-sm text-gray-700">Partially covered</span>
                            <span class="text-sm font-semibold text-[#C9A84C]">{{ $partial }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-[#C9A84C]" style="width: {{ round($partial / $totalCoverage * 100) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <span class="text-sm text-gray-700">Uncovered</span>
                            <span class="text-sm font-semibold text-[#C1272D]">{{ $uncovered }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-[#C1272D]" style="width: {{ round($uncovered / $totalCoverage * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
