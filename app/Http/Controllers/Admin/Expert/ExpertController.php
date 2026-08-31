<?php

namespace App\Http\Controllers\Expert\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Admin moderation over expert profiles (the public registry): list, search,
 * verify/unverify. An "expert" is a user with an `expert` profile row.
 */
class ExpertController extends Controller
{
    public function index(Request $request)
    {
        $like = str_contains(config('database.default'), 'pgsql') ? 'ilike' : 'like';

        $experts = User::query()
            ->whereHas('expert')
            ->with(['expert:id,user_id,specialization,is_verified,views', 'country:id,name'])
            ->when($request->filled('search'), function ($q) use ($request, $like) {
                $term = $request->search;
                $q->where(fn ($x) => $x->where('name', $like, "%{$term}%")->orWhere('email', $like, "%{$term}%"));
            })
            ->when($request->filled('verified'), function ($q) use ($request) {
                $q->where('is_verified', $request->verified === 'yes');
            })
            ->latest()
            ->paginate(20)
            ->withQueryString()
            ->through(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'external_id' => $u->external_id,
                'profile_image' => $u->profile_image,
                'specialization' => $u->expert?->specialization,
                'is_verified' => (bool) $u->is_verified,
                'views' => (int) ($u->expert?->views ?? 0),
                'country' => $u->country?->name,
                'created_at' => $u->created_at,
                'public_url' => $u->external_id ? route('experts.profile', $u->external_id) : null,
            ]);

        return Inertia::render('Admin/Experts/Index', [
            'experts' => $experts,
            'filters' => $request->only(['search', 'verified']),
            'stats' => [
                'total' => User::whereHas('expert')->count(),
                'verified' => User::whereHas('expert')->where('is_verified', true)->count(),
            ],
        ]);
    }

    public function verify(User $user)
    {
        $user->is_verified = ! $user->is_verified;
        $user->save();
        if ($user->expert) {
            $user->expert()->update(['is_verified' => $user->is_verified]);
        }

        return back()->with('success', $user->is_verified ? 'Expert verified.' : 'Verification removed.');
    }
}
