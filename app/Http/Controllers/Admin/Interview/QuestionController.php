<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Actions\Interview\AddInterviewQuestion;
use App\Actions\Interview\RemoveInterviewQuestion;
use App\Actions\Interview\ReorderInterviewQuestions;
use App\Actions\Interview\UpdateInterviewQuestion;
use App\Enums\Interview\InterviewParticipantRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\ReorderInterviewQuestionsRequest;
use App\Http\Requests\Interview\StoreInterviewQuestionRequest;
use App\Http\Requests\Interview\UpdateInterviewQuestionRequest;
use App\Http\Resources\ContentParticipant\ParticipantResource;
use App\Http\Resources\Interview\InterviewQuestionResource;
use App\Http\Resources\Interview\InterviewResource;
use App\Models\Interview\Interview;
use App\Models\Interview\InterviewQuestion;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class QuestionController extends Controller
{
    public function index(Interview $interview): Response
    {
        $interview->load([
            'questions.asker',
            'questions.answers.answerer',
        ]);

        return Inertia::render('admin/interview/question/Index', [
            'interview' => [
                'id' => $interview->id,
                'title' => $interview->title,
            ],

            'questions' => InterviewQuestionResource::collection(
                $interview->questions,
            ),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Interviews', route('admin.interviews.index'))
                ->add(
                    $interview->title,
                    route('admin.interviews.edit', $interview),
                )
                ->add('Questions')
                ->toArray(),
        ]);
    }

    public function create(Interview $interview): Response
    {
        $interview->load('participants.user');

        $interviewers = $interview->participants
            ->where('role.slug', 'interviewer')
            ->values();

        return Inertia::render('admin/interview/question/Create', [
            'interview' => new InterviewResource($interview),

            'interviewers' => ParticipantResource::collection(
                $interviewers,
            ),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interviews', route('admin.interviews.index'))
                ->add(
                    $interview->title,
                    route('admin.interviews.edit', $interview),
                )
                ->add('Add Question')
                ->toArray(),
        ]);
    }

    public function store(
        StoreInterviewQuestionRequest $request,
        Interview $interview,
        AddInterviewQuestion $action,
    ): RedirectResponse {
        $question = $action->handle(
            $interview,
            $request->validated(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Question added successfully.')]);

        return to_route(
            'admin.interviews.questions.edit',
            $question,
        );
    }

    public function edit(Interview $interview, InterviewQuestion $question): Response
    {
        $question->load([
            'interview',
            'interview.participants.user',
            'answers.answerer.user',
            'asker',
            'answers.answerer',
        ]);

        $interviewers = $question->interview->participants
            ->where('role.slug', 'interviewer')
            ->values();

        return Inertia::render('admin/interview/question/Edit', [
            'interview' => new InterviewResource($interview),
            'question' => new InterviewQuestionResource($question),

            'interviewers' => ParticipantResource::collection(
                $interviewers,
            ),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interviews', route('admin.interviews.index'))
                ->add(
                    $question->interview->title,
                    route('admin.interviews.edit', $question->interview),
                )
                ->add('Edit Question')
                ->toArray(),
        ]);
    }

    public function update(
        UpdateInterviewQuestionRequest $request,
        Interview $interview,
        InterviewQuestion $question,
        UpdateInterviewQuestion $action,
    ): RedirectResponse {
        $action->handle(
            $question,
            $request->validated(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Question updated successfully.')]);

        return to_route(
            'admin.interviews.questions.edit',
            [
                'interview' => $interview,
                'question' => $question,
            ]
        );
    }

    public function destroy(
        InterviewQuestion $question,
        RemoveInterviewQuestion $action,
    ): RedirectResponse {
        $interview = $question->interview;

        $action->handle($question);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Question deleted successfully.')]);

        return to_route(
            'admin.interviews.edit',
            $interview,
        );
    }

    public function reorder(
        ReorderInterviewQuestionsRequest $request,
        Interview $interview,
        ReorderInterviewQuestions $action,
    ): RedirectResponse {
        $action->handle(
            $interview,
            $request->validated('questions'),
        );

        return back();
    }
}