<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Actions\Interview\AddInterviewAnswer;
use App\Actions\Interview\RemoveInterviewAnswer;
use App\Actions\Interview\UpdateInterviewAnswer;
use App\Enums\Interview\InterviewParticipantRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\StoreInterviewAnswerRequest;
use App\Http\Requests\Interview\UpdateInterviewAnswerRequest;
use App\Http\Resources\Interview\InterviewParticipantResource;
use App\Models\Interview\InterviewAnswer;
use App\Models\Interview\InterviewQuestion;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AnswerController extends Controller
{
    public function create(InterviewQuestion $question): Response
    {
        $question->load('interview', 'interview.participants.user');

        return Inertia::render(
            'admin/interview/answer/Create',
            [
                'question' => [
                    'id' => $question->id,
                    'question' => $question->question,
                    'interview' => [
                        'id' => $question->interview->id,
                        'title' => $question->interview->title,
                    ],
                ],
                'interviewees' => InterviewParticipantResource::collection(
                    $question->interview->participants->where(
                        'role',
                        InterviewParticipantRole::Interviewee
                    )
                ),
            ]
        );
    }

    public function store(
        StoreInterviewAnswerRequest $request,
        InterviewQuestion $question,
        AddInterviewAnswer $action,
    ): RedirectResponse {
        $action->handle(
            $question,
            $request->validated()
        );

        Inertia::flash(
            'success',
            'Answer added successfully.'
        );

        return to_route(
            'admin.interviews.questions.edit',
            $question
        );
    }

    public function edit(InterviewAnswer $answer): Response
    {
        $answer->load([
            'question.interview.participants.user',
            'answerer',
        ]);

        $interview = $answer->question->interview;

        return Inertia::render(
            'admin/interview/answer/Edit',
            [
                'answer' => [
                    'id' => $answer->id,
                    'answer' => $answer->answer,

                    'answered_by' => $answer->answerer
                        ? [
                            'id' => $answer->answerer->id,
                            'name' => $answer->answerer->name,
                            'email' => $answer->answerer->email,
                        ]
                        : null,

                    'question' => [
                        'id' => $answer->question->id,
                        'question' => $answer->question->question,
                    ],

                    'interview' => [
                        'id' => $interview->id,
                        'title' => $interview->title,
                    ],
                ],

                'interviewees' => InterviewParticipantResource::collection(
                    $interview->participants->where(
                        'role',
                        InterviewParticipantRole::Interviewee
                    )
                ),
            ]
        );
    }

    public function update(
        UpdateInterviewAnswerRequest $request,
        InterviewAnswer $answer,
        UpdateInterviewAnswer $action,
    ): RedirectResponse {
        $action->handle(
            $answer,
            $request->validated()
        );

        Inertia::flash(
            'success',
            'Answer updated successfully.'
        );

        return to_route(
            'admin.interviews.questions.edit',
            $answer->question_id
        );
    }

    public function destroy(
        InterviewAnswer $answer,
        RemoveInterviewAnswer $action,
    ): RedirectResponse {
        $question = $answer->question;

        $action->handle($answer);

        Inertia::flash(
            'success',
            'Answer deleted successfully.'
        );

        return to_route(
            'admin.interviews.questions.edit',
            $question
        );
    }
}
