<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Http\Controllers\Controller;
use App\Models\Interview\Interview;
use App\Models\Interview\InterviewAnswer;
use App\Models\Interview\InterviewQuestion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnswerController extends Controller
{
    // CREATE (Answer page)
    public function create(
        Interview $interview,
        InterviewQuestion $question
    ): Response
    {
        abort_unless(
            $question->interview_id === $interview->id,
            404
        );

        $interview->load([
            'participants' => fn($query) => $query
                ->where('role', 'interviewee')
                ->with([
                    'user:id,name,email,avatar',
                ]),
        ]);

        return Inertia::render('Admin/Interviews/Answers/Create', [
            'interview' => $interview,
            'question' => $question,
        ]);
    }

    // STORE
    public function store(Request $request, Interview $interview, InterviewQuestion $question)
    {
        $validated = $request->validate([
            'answer' => ['required', 'string'],
            'answered_by' => ['required', 'exists:users,id'],
        ]);

        $question->answers()->create([
            'answered_by' => $validated['answered_by'],
            'answer' => $validated['answer'],
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Answer Created Successfully!')]);
        return to_route('admin.interviews.questions.index', $question->interview_id);
    }

    // EDIT
    public function edit(Interview $interview,InterviewQuestion $question,InterviewAnswer $answer)
    {
        $interview->load([
            'participants' => function ($query) {
                $query->where('role', 'interviewee')
                    ->with('user');
            }
        ]);
        return Inertia::render('Admin/Interviews/Answers/Edit', [
            'interview' => $interview,
            'question' => $question->load('interviewer'),
            'answer' => $answer
        ]);
    }

    // UPDATE
    public function update(Request $request, Interview $interview, InterviewQuestion $question,InterviewAnswer $answer)
    {
        $validated = $request->validate([
            'answer' => ['required', 'string'],
            'answered_by' => ['required', 'exists:users,id'],
        ]);

        $answer->update($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Answer Updated Successfully!')]);
        return to_route('admin.interviews.questions.index', $interview->id);
    }

    // DELETE (optional)
    public function destroy(InterviewAnswer $answer)
    {
        $interviewId = $answer->interview_id;

        $answer->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Answer Deleted Successfully!')]);
        return to_route('admin.interviews.questions.index', $interviewId);
    }
}
