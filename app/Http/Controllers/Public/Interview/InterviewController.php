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
     * Number of interviews per page.
     */
    private const PER_PAGE = 12;

    /**
     * Display published interviews.
     */
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $query = Pipeline::send(Interview::query())
            ->through([
                new SearchFilter(
                    'title',
                    $filters['search'] ?? null,
                ),
            ])
            ->thenReturn();

        $query
            ->where('status', 'published')
            ->when(
                !empty($filters['type']),
                fn ($query) => $query->where(
                    'interview_type',
                    $filters['type'],
                ),
            )
            ->with($this->listingRelations());

        /*
         * Featured interview.
         *
         * If you have an is_featured column, add:
         * ->where('is_featured', true)
         *
         * Currently, the latest published interview
         * is used as the featured interview.
         */
        $featured = Interview::query()
            ->where('status', 'published')
            ->with($this->listingRelations())
            ->latest('published_at')
            ->latest('id')
            ->first();

        return Inertia::render('interviews/Index', [
            'featured' => $featured
                ? InterviewResource::make($featured)
                : null,

            'interviews' => Inertia::scroll(
                fn () => InterviewResource::collection(
                    $query
                        ->latest('published_at')
                        ->latest('id')
                        ->paginate(self::PER_PAGE)
                        ->withQueryString()
                )
            ),

            'filters' => [
                'search' => $filters['search'] ?? '',
                'type' => $filters['type'] ?? '',
            ],

            'types' => InterviewType::dropdown(),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interviews')
                ->toArray(),
        ]);
    }

    /**
     * Display a published interview.
     */
    public function show(Interview $interview): Response
    {
        abort_unless(
            $interview->status === 'published',
            404,
        );

        $interview->load($this->detailRelations());

        return Inertia::render('interviews/Show', [
            'interview' => InterviewResource::make($interview),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interviews', route('interviews.index'))
                ->add($interview->title)
                ->toArray(),
        ]);
    }

    /**
     * Relationships required for interview listings.
     */
    private function listingRelations(): array
    {
        return [
            'participants:id,participantable_type,participantable_id,user_id,participant_role_id,position,is_primary,contribution',

            'participants.user:id,name,username,avatar',

            'participants.role:id,name,slug',

            'media:id,mediable_type,mediable_id,collection,name,file_name,mime_type,extension,size,path,alt,metadata',
        ];
    }

    /**
     * Relationships required for interview details.
     */
    private function detailRelations(): array
    {
        return [
            ...$this->listingRelations(),

            'questions' => fn ($query) => $query
                ->select([
                    'id',
                    'interview_id',
                    'asked_by',
                    'question',
                    'position',
                ])
                ->orderBy('position'),

            'questions.asker:id,name,username,avatar',

            'questions.answers' => fn ($query) => $query
                ->select([
                    'id',
                    'question_id',
                    'answered_by',
                    'answer',
                ]),

            'questions.answers.answerer:id,name,username,avatar',
        ];
    }
}
