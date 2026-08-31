<?php

namespace App\Http\Controllers\Public\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\PublicProfileResource;
use App\Models\Country;
use App\Models\Industry;
use App\Models\Language;
use App\Models\Position;
use App\Models\User;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display public profiles.
     */
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $countryId = $request->integer('country_id');
        $positionId = $request->integer('position_id');
        $languageIds = $request->input('language_ids', []);
        $industryIds = $request->input('industry_ids', []);

        $profiles = User::query()
            ->with([
                'position:id,name',
                'country:id,name,code',
                'languages:id,name,native,code',
                'industries:id,name',
            ])

            //Public Profiles
            ->whereNotNull('username')
            ->where('is_active', true)

            //Search
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'ilike', "%{$search}%")
                            ->orWhere('username', 'ilike', "%{$search}%")
                            ->orWhere('headline', 'ilike', "%{$search}%");
                    });
                }
            )

            //Country
            ->when(
                $countryId,
                fn($query) => $query->where(
                    'country_id',
                    $countryId,
                ),
            )

            //Position
            ->when(
                $positionId,
                fn($query) => $query->where(
                    'position_id',
                    $positionId,
                ),
            )

            //Languages
            ->when(
                ! empty($languageIds),
                function ($query) use ($languageIds) {
                    $query->whereHas(
                        'languages',
                        fn($query) => $query->whereIn(
                            'languages.id',
                            $languageIds,
                        ),
                    );
                },
            )

            //Industries
            ->when(
                ! empty($industryIds),
                function ($query) use ($industryIds) {
                    $query->whereHas(
                        'industries',
                        fn($query) => $query->whereIn(
                            'industries.id',
                            $industryIds,
                        ),
                    );
                },
            )

            ->latest()
            ->paginate(24)
            ->withQueryString();

        return Inertia::render('profiles/Index', [
            'breadcrumbs' => BreadcrumbBuilder::make()->home()->add('Experts')->toArray(),
            'profiles' => PublicProfileResource::collection($profiles),

            'countries' => Country::query()
                ->select([
                    'id',
                    'name',
                    'code',
                ])
                ->orderBy('name')
                ->get(),

            'positions' => Position::query()
                ->select([
                    'id',
                    'name',
                ])
                ->orderBy('name')
                ->get(),

            'languages' => Language::query()
                ->select([
                    'id',
                    'name',
                    'native',
                    'code',
                ])
                ->orderBy('name')
                ->get(),

            'industries' => Industry::query()
                ->select([
                    'id',
                    'name',
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Display a public profile.
     */
    public function show(string $username): Response
    {
        $user = User::query()
            ->with([
                'position:id,name',
                'country:id,name,code',
                'languages:id,name,native,code',
                'industries:id,name',
            ])
            ->where('username', $username)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('profiles/Show', [
            'user' => new PublicProfileResource($user),
        ]);
    }
}
