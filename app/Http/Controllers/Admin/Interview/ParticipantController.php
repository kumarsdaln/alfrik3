<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Actions\Interview\AddInterviewParticipant;
use App\Actions\Interview\RemoveInterviewParticipant;
use App\Enums\Interview\InterviewParticipantRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\StoreInterviewParticipantRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Interview\Interview;
use App\Models\Interview\InterviewParticipant;
use App\Models\User;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ParticipantController extends Controller
{
    public function index(
        Request $request,
        Interview $interview
    ): Response {
        $users = User::query()
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request
                        ->string('search')
                        ->trim()
                        ->toString();

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    });
                }
            )
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $interview->load([
            'participants.user',
        ]);

        return Inertia::render(
            'admin/interview/participant/Index',
            [
                'interview' => [
                    'id' => $interview->id,
                    'title' => $interview->title,
                ],

                'users' => UserResource::collection($users),
                'participantRoles' => InterviewParticipantRole::dropdown(),
                'participants' => $interview
                    ->participants
                    ->map(fn (InterviewParticipant $participant) => [
                        'id' => $participant->id,
                        'role' => $participant->role?->option(),
                        'user' => $participant->user
                            ? [
                                'id' => $participant->user->id,
                                'name' => $participant->user->name,
                                'email' => $participant->user->email,
                            ]
                            : null,
                    ])
                    ->values(),
                'breadcrumbs' => BreadcrumbBuilder::make()
                    ->home()
                    ->add('Interviews', route('admin.interviews.index'))
                    ->add($interview->title, route('admin.interviews.edit', $interview->id))
                    ->add('Participants')
                    ->toArray(),    
            ]
        );
    }

    public function store(
        StoreInterviewParticipantRequest $request,
        Interview $interview,
        AddInterviewParticipant $action,
    ): RedirectResponse {
        $action->handle(
            $interview,
            $request->validated()
        );

        Inertia::flash(
            'success',
            'Participant added successfully.'
        );

        return back();
    }

    public function destroy(
        InterviewParticipant $participant,
        RemoveInterviewParticipant $action,
    ): RedirectResponse {
        $action->handle($participant);

        Inertia::flash(
            'success',
            'Participant removed successfully.'
        );

        return back();
    }
}