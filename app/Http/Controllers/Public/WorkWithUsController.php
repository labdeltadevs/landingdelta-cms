<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WorkWithUsController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
        ]);

        $search = trim($validated['search'] ?? '');

        $rawTitle = SiteSetting::get('work_with_us_title');
        $title = is_array($rawTitle) ? $rawTitle['body'] ?? '' : $rawTitle;

        $rawDesc = SiteSetting::get('work_with_us_description');
        $description = is_array($rawDesc) ? $rawDesc['body'] ?? '' : $rawDesc;

        $openings = JobOpening::query()
            ->published()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%'.$search.'%'))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('public.work-with-us.index', compact('title', 'description', 'openings', 'search'));
    }

    public function show(string $jobSlug): View
    {
        $jobOpening = JobOpening::query()
            ->published()
            ->where('slug', $jobSlug)
            ->firstOrFail();

        return view('public.work-with-us.show', compact('jobOpening'));
    }
}
