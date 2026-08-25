<?php

namespace App\Http\Controllers\Public\Interview;

use App\Enums\Interview\Type;
use App\Filters\CategoryFilter;
use App\Filters\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Interview\InterviewResource;
use App\Models\Interview\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Inertia\Inertia;

class InterviewController extends Controller
{
    public function index(Request $request)
    {
        $items_on_page = 12;

        $pipes = [
            new SearchFilter('title', $request->input('search')),
        ];

        $query = Pipeline::send(Interview::query())
            ->through($pipes)
            ->thenReturn();

        return Inertia::render('Interviews/Index', [
            'interviews' => Inertia::scroll(
                fn() =>
                InterviewResource::collection(
                    $query
                        ->with([
                            'participants:id,interview_id,user_id',
                            'participants.user:id,name,avatar,external_id',
                        ])
                        ->where('status', 'published')
                        ->latest('published_at')
                        ->paginate($items_on_page)
                        ->withQueryString()
                )
            ),
            'qfilters' => [
                'search' => $request->search,
                'type' => $request->type,
            ],
            'types' => Type::dropdown()
        ]);
    }

    public function show(Interview $interview)
    {
        $interview->load([
            'participants:id,interview_id,user_id,role',
            'participants.user:id,name,avatar,external_id',

            'questions:id,interview_id,asked_by,question,order',
            'questions.interviewer:id,name,avatar,external_id',

            'questions.answers:id,question_id,answered_by,answer',
            'questions.answers.answeredBy:id,name,avatar,external_id',

            'media:id,interview_id,media_type,source_type,file_url,embed_url,thumbnail,duration',
        ]);

        return Inertia::render('Interviews/Show', [
            'interview' => InterviewResource::make($interview),
        ]);
    }
}
