<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Http\Controllers\Controller;
use App\Models\Interview\Interview;
use App\Models\Interview\InterviewQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /* ======================
       INDEX
    ====================== */
    public function index(Interview $interview)
    {
        $interview->load('questions.interviewer', 'questions.answers.answeredBy');

        return inertia('Admin/Interviews/Questions/Index', [
            'interview' => $interview
        ]);
    }

    /* ======================
       CREATE
    ====================== */
    public function create(Interview $interview)
    {
        $interview->load([
            'participants' => function ($query) {
                $query->where('role', 'interviewer')
                    ->with('user');
            }
        ]);
        return inertia('Admin/Interviews/Questions/Create', [
            'interview' => $interview
        ]);
    }

    /* ======================
       STORE
    ====================== */
    public function store(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'asked_by' => ['required', 'exists:users,id'],
        ]);

        $order = $interview->questions()->max('order') + 1;

        $interview->questions()->create([
            'question' => $validated['question'],
            'asked_by' => $validated['asked_by'],
            'order' => $order ?? 1,
        ]);

        return redirect()
            ->route('admin.interviews.questions.index', $interview->id)
            ->with('success', 'Question added');
    }

    /* ======================
       EDIT
    ====================== */
    public function edit(Interview $interview, InterviewQuestion $question)
    {

        $interview->load([
            'participants' => function ($query) {
                $query->where('role', 'interviewer')
                    ->with('user');
            }
        ]);
        return inertia('Admin/Interviews/Questions/Edit', [
            'interview' => $interview,
            'question' => $question
        ]);
    }

    /* ======================
       UPDATE
    ====================== */
    public function update(Request $request, Interview $interview, InterviewQuestion $question)
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'asked_by' => ['required', 'exists:users,id'],
        ]);

        $question->update($validated);

        return redirect()
            ->route('admin.interviews.questions.index', $interview->id)
            ->with('success', 'Question updated');
    }

    /* ======================
       DELETE
    ====================== */
    public function destroy(Interview $interview, InterviewQuestion $question)
    {
        $question->delete();
        return redirect()->back()
            ->with('success', 'Question deleted');
    }

    public function reorder(
        Request $request,
        Interview $interview
    ) {
        $request->validate([
            'questions' => ['required', 'array'],
            'questions.*.id' => ['required', 'exists:interview_questions,id'],
            'questions.*.order' => ['required', 'integer'],
        ]);

        foreach ($request->questions as $item) {
            InterviewQuestion::find($item['id'])
                ->update([
                    'order' => $item['order'],
                ]);
        }

        return back();
    }
}
