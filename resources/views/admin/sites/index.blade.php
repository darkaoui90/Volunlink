@extends('layouts.admin')
@section('title', 'Sites')

@section('content')
@php
    $typePalette = [
        'Stadium' => 'bg-red-50 text-[#C1272D]',
        'Fan Zone' => 'bg-green-50 text-[#006233]',
        'Media Center' => 'bg-amber-50 text-[#C9A84C]',
        'Transport Hub' => 'bg-blue-50 text-blue-700',
        'Operations Center' => 'bg-slate-100 text-slate-700',
        'Other' => 'bg-gray-100 text-gray-600',
    ];
    $typeOptions = ['Stadium', 'Fan Zone', 'Media Center', 'Transport Hub', 'Operations Center', 'Other'];
    $totalMissionLinks = $sites->sum('missions_count');
    $citiesCovered = $sites->pluck('city')->filter()->unique()->count();
    $averageCapacity = (int) round((float) ($sites->whereNotNull('capacity')->avg('capacity') ?? 0));
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap gap-3">
        <div class="rounded-full bg-gray-100 px-4 py-1.5 text-sm font-medium text-gray-700">
            Total sites: {{ $sites->count() }}
        </div>
        <div class="rounded-full bg-red-50 px-4 py-1.5 text-sm font-medium text-[#C1272D]">
            Linked missions: {{ $totalMissionLinks }}
        </div>
        <div class="rounded-full bg-green-50 px-4 py-1.5 text-sm font-medium text-[#006233]">
            Cities covered: {{ $citiesCovered }}
        </div>
        <div class="rounded-full bg-blue-50 px-4 py-1.5 text-sm font-medium text-blue-700">
            Avg capacity: {{ number_format($averageCapacity) }}
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.7fr_1fr]">
        <section class="space-y-4">
            @forelse ($sites as $site)
                @php
                    $badgeClass = $typePalette[$site->type] ?? $typePalette['Other'];
                    $isEditing = $editingSite?->is($site);
                @endphp
                <article class="rounded-xl border bg-white p-5 transition-all {{ $isEditing ? 'border-[#C1272D] shadow-sm' : 'border-gray-100' }}">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-lg font-semibold text-gray-900">{{ $site->name }}</h2>
                                <span class="rounded-full px-2 py-1 text-xs font-medium {{ $badgeClass }}">
                                    {{ $site->type }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">{{ $site->city }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $site->address ?: 'Address not set' }}</p>
                        </div>

                        <div class="text-right">
                            <p class="text-xs uppercase tracking-wide text-gray-400">Linked missions</p>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $site->missions_count }}</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 border-t border-gray-100 pt-4 text-sm text-gray-600 md:grid-cols-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-400">Capacity</p>
                            <p class="mt-1 font-medium text-gray-900">
                                {{ $site->capacity !== null ? number_format($site->capacity) : 'Not set' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-400">Coordinates</p>
                            <p class="mt-1 font-medium text-gray-900">
                                @if ($site->latitude !== null && $site->longitude !== null)
                                    {{ $site->latitude }}, {{ $site->longitude }}
                                @else
                                    Not set
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-400">Description</p>
                            <p class="mt-1 line-clamp-2 font-medium text-gray-900">
                                {{ $site->description ?: 'No description provided.' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-gray-100 pt-4">
                        <a href="{{ route('admin.sites.edit', $site) }}" class="rounded-lg border border-[#006233] px-3 py-1.5 text-xs font-medium text-[#006233] transition-colors hover:bg-green-50">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.sites.destroy', $site) }}" onsubmit="return confirm('Delete this site? Missions linked to it will block deletion.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-[#C1272D] px-3 py-1.5 text-xs font-medium text-[#C1272D] transition-colors hover:bg-red-50">
                                Delete
                            </button>
                        </form>
                        @if ($isEditing)
                            <a href="{{ route('admin.sites.index') }}" class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-200">
                                Close editor
                            </a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center">
                    <p class="text-lg font-semibold text-gray-900">No sites yet</p>
                    <p class="mt-2 text-sm text-gray-500">Create your first site to start linking missions to real locations.</p>
                </div>
            @endforelse
        </section>

        <aside class="space-y-6">
            <div class="rounded-xl border border-gray-100 bg-white p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $editingSite ? 'Edit site' : 'New site' }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $editingSite ? 'Update site details and linked mission context.' : 'Add a real site record for mission planning.' }}
                        </p>
                    </div>
                    @if ($editingSite)
                        <a href="{{ route('admin.sites.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                            New site
                        </a>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $editingSite ? route('admin.sites.update', $editingSite) : route('admin.sites.store') }}" class="mt-5 space-y-4">
                    @csrf
                    @if ($editingSite)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Site name</label>
                        <input id="name" name="name" type="text" required value="{{ old('name', $editingSite?->name) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]">
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="city" class="mb-1 block text-sm font-medium text-gray-700">City</label>
                            <input id="city" name="city" type="text" required value="{{ old('city', $editingSite?->city) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]">
                        </div>
                        <div>
                            <label for="type" class="mb-1 block text-sm font-medium text-gray-700">Type</label>
                            <select id="type" name="type" required class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]">
                                @foreach ($typeOptions as $typeOption)
                                    <option value="{{ $typeOption }}" @selected(old('type', $editingSite?->type ?? 'Stadium') === $typeOption)>
                                        {{ $typeOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="address" class="mb-1 block text-sm font-medium text-gray-700">Address</label>
                        <input id="address" name="address" type="text" value="{{ old('address', $editingSite?->address) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]">
                    </div>

                    <div>
                        <label for="capacity" class="mb-1 block text-sm font-medium text-gray-700">Capacity</label>
                        <input id="capacity" name="capacity" type="number" min="0" value="{{ old('capacity', $editingSite?->capacity) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]">
                    </div>

                    <div>
                        <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]">{{ old('description', $editingSite?->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="latitude" class="mb-1 block text-sm font-medium text-gray-700">Latitude</label>
                            <input id="latitude" name="latitude" type="number" step="0.0000001" value="{{ old('latitude', $editingSite?->latitude) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]">
                        </div>
                        <div>
                            <label for="longitude" class="mb-1 block text-sm font-medium text-gray-700">Longitude</label>
                            <input id="longitude" name="longitude" type="number" step="0.0000001" value="{{ old('longitude', $editingSite?->longitude) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]">
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" class="rounded-lg bg-[#C1272D] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#8B1A1F]">
                            {{ $editingSite ? 'Update site' : 'Create site' }}
                        </button>
                        @if ($editingSite)
                            <a href="{{ route('admin.sites.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200">
                                Cancel
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if ($editingSite)
                <div class="rounded-xl border border-gray-100 bg-white p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Linked missions</h3>
                            <p class="mt-1 text-sm text-gray-500">Missions currently attached to this site.</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                            {{ $editingSite->missions->count() }} mission{{ $editingSite->missions->count() === 1 ? '' : 's' }}
                        </span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($editingSite->missions as $mission)
                            <a href="{{ route('admin.missions.show', $mission) }}" class="block rounded-lg border border-gray-100 bg-gray-50 px-4 py-3 transition-colors hover:border-[#C1272D] hover:bg-white">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $mission->title }}</p>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $mission->date }} | {{ $mission->start_time }} - {{ $mission->end_time }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="rounded-full px-2 py-1 text-xs font-medium {{ $mission->status_label === 'Completed' ? 'bg-slate-100 text-slate-700' : ($mission->status_label === 'Ongoing' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ $mission->status_label }}
                                        </span>
                                        <p class="mt-2 text-xs text-gray-500">
                                            {{ $mission->volunteers_count }}/{{ $mission->required_volunteers }} volunteers
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">
                                No missions are linked to this site yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
