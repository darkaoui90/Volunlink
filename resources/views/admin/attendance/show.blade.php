@extends('layouts.admin')
@section('title', 'Mission Attendance')

@section('content')
<div class="space-y-6">
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-medium">Attendance update failed.</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Mission Attendance</h1>
            <p class="mt-1 text-sm text-gray-600">Update attendance for volunteers assigned to this mission.</p>
        </div>
        <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to Attendance
        </a>
    </div>

    <div class="grid gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 lg:col-span-2">
            <p class="text-sm text-gray-500">Mission</p>
            <p class="mt-2 text-xl font-semibold text-gray-900">{{ $mission->title }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Date</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $mission->date }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Location</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $mission->display_location }}</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Assigned Volunteers</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $mission->volunteers->count() }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Present</p>
            <p class="mt-2 text-3xl font-semibold text-green-600">{{ $mission->volunteers->where('pivot.status', 'present')->count() }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Absent</p>
            <p class="mt-2 text-3xl font-semibold text-red-600">{{ $mission->volunteers->where('pivot.status', 'absent')->count() }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Late</p>
            <p class="mt-2 text-3xl font-semibold text-amber-500">{{ $mission->volunteers->where('pivot.status', 'late')->count() }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">Assigned Volunteers</h2>
        </div>

        @if ($mission->volunteers->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-gray-500">
                No volunteers are assigned to this mission yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Volunteer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Current Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Mark Attendance</th>
                        </tr>
                    </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($mission->volunteers as $volunteer)
                                @php
                                    $status = $volunteer->pivot->status ?? 'assigned';
                                    $totalLateMinutes = (int) ($volunteer->pivot->late_minutes ?? 0);
                                    $lateHours = intdiv($totalLateMinutes, 60);
                                    $lateRemainderMinutes = $totalLateMinutes % 60;
                                    $lateFormOpen = old('status') === 'late'
                                        && (string) old('volunteer_id') === (string) $volunteer->id;
                                @endphp
                                <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $volunteer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $volunteer->email }}</div>
                                </td>
                                    <td class="px-6 py-4">
                                        @if ($status === 'present')
                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Present</span>
                                        @elseif ($status === 'absent')
                                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">Absent</span>
                                        @elseif ($status === 'late')
                                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">Late</span>
                                            @if ($totalLateMinutes > 0)
                                                <p class="mt-2 text-xs text-gray-500">
                                                    {{ $lateHours > 0 ? $lateHours . 'h ' : '' }}{{ $lateRemainderMinutes }}m late
                                                </p>
                                            @endif
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">Assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-3">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <form action="{{ route('admin.attendance.update', [$mission, $volunteer]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="present">

                                                    <button type="submit" class="rounded-lg px-3 py-2 text-xs font-medium {{ $status === 'present' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                                        Present
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.attendance.update', [$mission, $volunteer]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="absent">

                                                    <button type="submit" class="rounded-lg px-3 py-2 text-xs font-medium {{ $status === 'absent' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                                        Absent
                                                    </button>
                                                </form>

                                                <button
                                                    type="button"
                                                    data-late-toggle="{{ $volunteer->id }}"
                                                    class="rounded-lg px-3 py-2 text-xs font-medium {{ $status === 'late' || $lateFormOpen ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700 hover:bg-amber-200' }}"
                                                >
                                                    Late
                                                </button>
                                            </div>

                                            <form
                                                action="{{ route('admin.attendance.update', [$mission, $volunteer]) }}"
                                                method="POST"
                                                data-late-form="{{ $volunteer->id }}"
                                                class="{{ $lateFormOpen ? 'flex' : 'hidden' }} flex-wrap items-end gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="late">
                                                <input type="hidden" name="volunteer_id" value="{{ $volunteer->id }}">

                                                <div>
                                                    <label for="late-hours-{{ $volunteer->id }}" class="mb-1 block text-xs font-medium text-amber-900">Hours</label>
                                                    <input
                                                        id="late-hours-{{ $volunteer->id }}"
                                                        type="number"
                                                        name="late_hours"
                                                        min="0"
                                                        value="{{ old('late_hours', $status === 'late' ? $lateHours : 0) }}"
                                                        class="w-20 rounded-lg border border-amber-300 bg-white px-3 py-2 text-xs text-gray-700"
                                                    >
                                                </div>

                                                <div>
                                                    <label for="late-minutes-{{ $volunteer->id }}" class="mb-1 block text-xs font-medium text-amber-900">Minutes</label>
                                                    <input
                                                        id="late-minutes-{{ $volunteer->id }}"
                                                        type="number"
                                                        name="late_minutes"
                                                        min="0"
                                                        max="59"
                                                        value="{{ old('late_minutes', $status === 'late' ? $lateRemainderMinutes : 0) }}"
                                                        class="w-24 rounded-lg border border-amber-300 bg-white px-3 py-2 text-xs text-gray-700"
                                                    >
                                                </div>

                                                <button type="submit" class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-medium text-white hover:bg-amber-600">
                                                    Save late
                                                </button>

                                                <button
                                                    type="button"
                                                    data-late-cancel
                                                    class="rounded-lg border border-amber-300 bg-white px-3 py-2 text-xs font-medium text-amber-700 hover:bg-amber-100"
                                                >
                                                    Cancel
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lateForms = document.querySelectorAll('[data-late-form]');

        document.querySelectorAll('[data-late-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                const targetId = button.getAttribute('data-late-toggle');

                lateForms.forEach(function (form) {
                    const isTarget = form.getAttribute('data-late-form') === targetId;
                    const shouldHide = !isTarget || !form.classList.contains('hidden');

                    form.classList.toggle('hidden', shouldHide);
                });

                const targetForm = document.querySelector('[data-late-form="' + targetId + '"]');

                if (targetForm && !targetForm.classList.contains('hidden')) {
                    const firstInput = targetForm.querySelector('input[name="late_hours"]');

                    if (firstInput) {
                        firstInput.focus();
                    }
                }
            });
        });

        document.querySelectorAll('[data-late-cancel]').forEach(function (button) {
            button.addEventListener('click', function () {
                const form = button.closest('[data-late-form]');

                if (form) {
                    form.classList.add('hidden');
                }
            });
        });
    });
</script>
@endsection
