@extends('layouts.admin')
@section('title', 'Attendance')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Attendance</h1>
        <p class="mt-1 text-sm text-gray-600">Choose a mission and mark assigned volunteers as present, absent, or late.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Assigned</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Present</p>
            <p class="mt-2 text-3xl font-semibold text-green-600">{{ $stats['present'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Absent</p>
            <p class="mt-2 text-3xl font-semibold text-red-600">{{ $stats['absent'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Late</p>
            <p class="mt-2 text-3xl font-semibold text-amber-500">{{ $stats['late'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Still Assigned</p>
            <p class="mt-2 text-3xl font-semibold text-slate-700">{{ $stats['total'] - $stats['present'] - $stats['absent'] - $stats['late'] }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">Mission Attendance</h2>
        </div>

        @if ($missions->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-gray-500">
                No missions with assigned volunteers yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Mission</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Assigned</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Summary</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($missions as $mission)
                            @php
                                $present = $mission->volunteers->where('pivot.status', 'present')->count();
                                $absent = $mission->volunteers->where('pivot.status', 'absent')->count();
                                $late = $mission->volunteers->where('pivot.status', 'late')->count();
                                $assigned = $mission->volunteers->count() - $present - $absent - $late;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $mission->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mission->date }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mission->display_location }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mission->volunteers->count() }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">{{ $assigned }} Assigned</span>
                                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">{{ $present }} Present</span>
                                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">{{ $absent }} Absent</span>
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">{{ $late }} Late</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.attendance.show', $mission) }}" class="inline-flex items-center rounded-lg bg-[#C1272D] px-4 py-2 text-sm font-medium text-white hover:bg-[#8B1A1F]">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
