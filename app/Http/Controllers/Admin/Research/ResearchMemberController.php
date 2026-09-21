<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\AddResearchMember;
use App\Actions\Research\RemoveResearchMember;
use App\Actions\Research\UpdateResearchMember;
use App\Enums\Research\ResearchMemberRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Research\AddResearchMemberRequest;
use App\Http\Requests\Admin\Research\UpdateResearchMemberRequest;
use App\Http\Resources\Research\ResearchMemberResource;
use App\Models\Research\Research;
use App\Models\Research\ResearchMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ResearchMemberController extends Controller
{
    public function index(
        Research $research,
    ): Response {
        $members = $research->members()
            ->with('user')
            ->orderBy('role')
            ->orderBy('joined_at')
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
            ]);

        return Inertia::render(
            'admin/research/team/Index',
            [
                'research' => [
                    'id' => $research->id,
                    'title' => $research->title,
                ],

                'members' => ResearchMemberResource::collection(
                    $members
                ),

                'users' => $users,

                'roleOptions' => collect(
                    ResearchMemberRole::cases()
                )
                    ->map(fn(ResearchMemberRole $role) => [
                        'value' => $role->value,
                        'label' => $role->label(),
                    ])
                    ->values()
                    ->all(),
            ]
        );
    }

    public function store(
        AddResearchMemberRequest $request,
        Research $research,
        AddResearchMember $action,
    ): RedirectResponse {
        $data = $request->validated();

        $exists = $research->members()
            ->where('user_id', $data['user_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'user_id' => 'This user is already a member of this research.',
            ]);
        }

        $action->handle(
            $research,
            $data['user_id'],
            $data['role'],
        );

        return back()->with(
            'success',
            'Research member added successfully.'
        );
    }

    public function update(
        UpdateResearchMemberRequest $request,
        Research $research,
        ResearchMember $member,
        UpdateResearchMember $action,
    ): RedirectResponse {
        $this->ensureMemberBelongsToResearch(
            $research,
            $member
        );

        $action->handle(
            $member,
            $request->validated('role')
        );

        return back()->with(
            'success',
            'Research member updated successfully.'
        );
    }

    public function destroy(
        Research $research,
        ResearchMember $member,
        RemoveResearchMember $action,
    ): RedirectResponse {
        $this->ensureMemberBelongsToResearch(
            $research,
            $member
        );

        $action->handle($member);

        return back()->with(
            'success',
            'Research member removed successfully.'
        );
    }

    private function ensureMemberBelongsToResearch(
        Research $research,
        ResearchMember $member,
    ): void {
        abort_unless(
            $member->research_id === $research->id,
            404
        );
    }
}
