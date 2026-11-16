<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Actions\Interview\AddInterviewAnswer;
use App\Actions\Interview\RemoveInterviewAnswer;
use App\Actions\Interview\UpdateInterviewAnswer;
use App\Enums\Interview\InterviewParticipantRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\StoreInterviewAnswerRequest;
use App\Http\Requests\Interview\UpdateInterviewAnswerRequest;
use App\Http\Resources\ContentParticipant\ParticipantResource;
use App\Http\Resources\Interview\InterviewAnswerResource;
use App\Http\Resources\Interview\InterviewQuestionResource;
use App\Http\Resources\Interview\InterviewResource;
use App\Models\Interview\Interview;
use App\Models\Interview\InterviewAnswer;
use App\Models\Interview\InterviewQuestion;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AnswerController extends Controller
{
    public function create(Interview $interview, InterviewQuestion $question): Response
    {
        $interview->load([
            'participants.user',
            'participants.role',
        ]);
        $interviewees = $interview->participants
            ->where('role.slug', 'interviewee')
            ->values();

        return Inertia::render('admin/interview/answer/Create', [
            'interview' => InterviewResource::make($interview),
            'question' => InterviewQuestionResource::make($question),
            'interviewees' => ParticipantResource::collection($interviewees,),
        ]);
    }

    public function store(
        StoreInterviewAnswerRequest $request,
        InterviewQuestion $question,
        AddInterviewAnswer $action,
    ): RedirectResponse {
        $action->handle(
            $question,
            $request->validated(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Answer created successfully.')]);
        return to_route(
            'admin.interviews.questions.edit',
            $question,
        );
    }

    public function edit(
        Interview $interview,
        InterviewQuestion $question,
        InterviewAnswer $answer
    ): Response {
        $interview->load([
            'participants.user',
            'participants.role',
        ]);
        $interviewees = $interview->participants
            ->where('role.slug', 'interviewee')
            ->values();
        $answer->load([
            'answerer.user',
            'answerer.role',
        ]);
        return Inertia::render('admin/interview/answer/Edit', [
            'interview' => InterviewResource::make($interview),
            'question' => InterviewQuestionResource::make($question),
            'answer' => InterviewAnswerResource::make($answer),
            'interviewees' => ParticipantResource::collection(
                $interviewees,
            ),
        ]);
    }

    public function update(
        UpdateInterviewAnswerRequest $request,
        Interview $interview,
        InterviewQuestion $question,
        InterviewAnswer $answer,
        UpdateInterviewAnswer $action,
    ): RedirectResponse {
        $action->handle(
            $answer,
            $request->validated(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Answer updated successfully.')]);
        return to_route(
            'admin.interviews.questions.edit',
            [
                'interview' => $interview,
                'question' => $question,
            ]
        );
    }

    public function destroy(
        Interview $interview,
        InterviewQuestion $question,
        InterviewAnswer $answer,
        RemoveInterviewAnswer $action,
    ): RedirectResponse {
        $question = $answer->question;

        $action->handle($answer);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Answer deleted successfully.')]);
        return to_route(
            'admin.interviews.questions.edit',
            [
                'interview' => $interview,
                'question' => $question,
            ]
        );
    }
}
