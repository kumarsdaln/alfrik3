<?php

namespace App\Http\Controllers\Public\Interview;

use App\Enums\Interview\InterviewType;
use App\Filters\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Interview\InterviewResource;
use App\Models\Interview\Interview;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Inertia\Inertia;
use Inertia\Response;

class InterviewController extends Controller
{
    /**
     * Display published interviews.
     */
    public function index(Request $request): Response
    {
        $itemsOnPage = 12;

        $pipes = [
            new SearchFilter(
                'title',
                $request->input('search')
            ),
        ];

        $query = Pipeline::send(Interview::query())
            ->through($pipes)
            ->thenReturn();

        if ($request->filled('type')) {
            $query->where(
                'interview_type',
                $request->input('type')
            );
        }

        $query
            ->where('status', 'published')
            ->with([
                'participants:id,interview_id,user_id,role',
                'participants.user:id,name,username,avatar',

                'media:id,mediable_type,mediable_id,collection,name,file_name,mime_type,extension,size,path,alt,metadata',
            ]);

        /*
     * Featured interview
     */
        $featured = Interview::query()
            ->where('status', 'published')
            // ->where('is_featured', true)
            ->with([
                'participants:id,interview_id,user_id,role',
                'participants.user:id,name,username,avatar',

                'media:id,mediable_type,mediable_id,collection,name,file_name,mime_type,extension,size,path,alt,metadata',
            ])
            ->latest('published_at')
            ->first();

        return Inertia::render('interviews/Index', [
            /*
         * Featured interview
         */
            'featured' => $featured
                ? InterviewResource::make($featured)
                : null,

            /*
         * Interview listing
         */
            'interviews' => Inertia::scroll(
                fn() => InterviewResource::collection(
                    $query
                        ->latest('published_at')
                        ->paginate($itemsOnPage)
                        ->withQueryString()
                )
            ),

            /*
         * Current filters
         */
            'filters' => [
                'search' => $request->input('search', ''),
                'type' => $request->input('type', ''),
            ],

            /*
         * Interview types
         */
            'types' => InterviewType::dropdown(),

            /*
         * Breadcrumbs
         */
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interview')
                ->toArray(),
        ]);
    }

    /**
     * Display the specified interview.
     */
    public function show(Interview $interview)
    {
        $interview->load([
            'participants:id,interview_id,user_id,role',
            'participants.user:id,name,username,avatar',

            'questions:id,interview_id,asked_by,question,position',
            'questions.asker:id,name,username,avatar',

            'questions.answers:id,question_id,answered_by,answer',
            'questions.answers.answerer:id,name,username,avatar',

            'media:id,mediable_type,mediable_id,collection,name,path,file_name,mime_type,extension,size,alt,metadata',
        ]);

        return Inertia::render('interviews/Show', [
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interview', route('interviews.index'))
                ->add($interview->title)
                ->toArray(),

            'interview' => InterviewResource::make($interview),
        ]);
    }
}
