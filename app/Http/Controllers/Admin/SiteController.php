<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        return $this->renderIndex();
    }

    public function edit(Site $site): View
    {
        return $this->renderIndex($site);
    }

    public function store(Request $request): RedirectResponse
    {
        $site = Site::create($this->validatedData($request));

        return redirect()
            ->route('admin.sites.edit', $site)
            ->with('success', 'Site created successfully.');
    }

    public function update(Request $request, Site $site): RedirectResponse
    {
        $site->update($this->validatedData($request, $site));

        return redirect()
            ->route('admin.sites.edit', $site)
            ->with('success', 'Site updated successfully.');
    }

    public function destroy(Site $site): RedirectResponse
    {
        if ($site->missions()->exists()) {
            return redirect()
                ->route('admin.sites.edit', $site)
                ->with('error', 'This site is linked to existing missions and cannot be deleted yet.');
        }

        $site->delete();

        return redirect()
            ->route('admin.sites.index')
            ->with('success', 'Site deleted successfully.');
    }

    private function renderIndex(?Site $editingSite = null): View
    {
        $sites = Site::query()
            ->withCount('missions')
            ->orderBy('city')
            ->orderBy('name')
            ->get();

        if ($editingSite !== null) {
            $editingSite->load([
                'missions' => fn ($query) => $query
                    ->withCount('volunteers')
                    ->orderBy('date')
                    ->orderBy('start_time'),
            ]);
        }

        return view('admin.sites.index', [
            'sites' => $sites,
            'editingSite' => $editingSite,
        ]);
    }

    private function validatedData(Request $request, ?Site $site = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sites', 'name')->ignore($site?->id),
            ],
            'city' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    }
}
