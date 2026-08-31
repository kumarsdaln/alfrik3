<?php

namespace Database\Seeders;

use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $categories = EventCategory::query()
            ->get()
            ->keyBy('slug');

        $events = [
            [
                'title' => 'The Future of Artificial Intelligence in Global Business',
                'slug' => 'future-of-artificial-intelligence-in-global-business',
                'description' => 'A multidisciplinary discussion examining how artificial intelligence is reshaping business, labour, innovation and economic decision-making across global markets.',
                'event_type' => 'offline',
                'visibility' => 'public',
                'start_date' => now()->addDays(18)->setTime(10, 0),
                'end_date' => now()->addDays(18)->setTime(16, 30),
                'location_name' => 'London School of Economics',
                'address' => 'Houghton Street',
                'city' => 'London',
                'state' => 'England',
                'country' => 'United Kingdom',
                'meeting_url' => null,
                'banner' => null,
                'max_attendees' => 250,
                'status' => 'published',
                'categories' => [
                    'conferences',
                    'seminars',
                ],
            ],

            [
                'title' => 'Africa and the New Global Economy',
                'slug' => 'africa-and-the-new-global-economy',
                'description' => 'Leading economists, entrepreneurs and policy experts explore Africa’s changing position in the global economy and the opportunities shaping its next decade.',
                'event_type' => 'hybrid',
                'visibility' => 'public',
                'start_date' => now()->addDays(32)->setTime(14, 0),
                'end_date' => now()->addDays(32)->setTime(17, 30),
                'location_name' => 'University of Nairobi',
                'address' => 'University Way',
                'city' => 'Nairobi',
                'state' => 'Nairobi County',
                'country' => 'Kenya',
                'meeting_url' => 'https://example.com/events/africa-global-economy',
                'banner' => null,
                'max_attendees' => 180,
                'status' => 'published',
                'categories' => [
                    'conferences',
                    'panel-discussions',
                ],
            ],

            [
                'title' => 'Research Methods for Emerging Policy Questions',
                'slug' => 'research-methods-for-emerging-policy-questions',
                'description' => 'An intensive workshop on research design, evidence evaluation and practical methods for investigating complex policy questions.',
                'event_type' => 'online',
                'visibility' => 'public',
                'start_date' => now()->addDays(45)->setTime(11, 0),
                'end_date' => now()->addDays(45)->setTime(14, 0),
                'location_name' => null,
                'address' => null,
                'city' => null,
                'state' => null,
                'country' => null,
                'meeting_url' => 'https://example.com/events/research-methods',
                'banner' => null,
                'max_attendees' => 100,
                'status' => 'published',
                'categories' => [
                    'workshops',
                    'seminars',
                ],
            ],

            [
                'title' => 'Cities, Climate and the Future of Urban Life',
                'slug' => 'cities-climate-and-the-future-of-urban-life',
                'description' => 'A public conversation about urban development, climate resilience and the choices cities must make as populations and environmental pressures continue to grow.',
                'event_type' => 'offline',
                'visibility' => 'public',
                'start_date' => now()->addDays(61)->setTime(18, 0),
                'end_date' => now()->addDays(61)->setTime(20, 0),
                'location_name' => 'Barbican Centre',
                'address' => 'Silk Street',
                'city' => 'London',
                'state' => 'England',
                'country' => 'United Kingdom',
                'meeting_url' => null,
                'banner' => null,
                'max_attendees' => 300,
                'status' => 'published',
                'categories' => [
                    'public-lectures',
                    'panel-discussions',
                ],
            ],

            [
                'title' => 'The Changing Landscape of Global Education',
                'slug' => 'changing-landscape-of-global-education',
                'description' => 'Educators and researchers discuss technology, access, mobility and the changing expectations of higher education around the world.',
                'event_type' => 'offline',
                'visibility' => 'public',
                'start_date' => now()->subDays(20)->setTime(10, 0),
                'end_date' => now()->subDays(20)->setTime(15, 0),
                'location_name' => 'University of Oxford',
                'address' => 'University Parks',
                'city' => 'Oxford',
                'state' => 'England',
                'country' => 'United Kingdom',
                'meeting_url' => null,
                'banner' => null,
                'max_attendees' => 200,
                'status' => 'published',
                'categories' => [
                    'conferences',
                    'public-lectures',
                ],
            ],

            [
                'title' => 'Digital Society and Democratic Institutions',
                'slug' => 'digital-society-and-democratic-institutions',
                'description' => 'A panel examining the relationship between digital platforms, public institutions, civic participation and democratic governance.',
                'event_type' => 'hybrid',
                'visibility' => 'public',
                'start_date' => now()->subDays(48)->setTime(16, 0),
                'end_date' => now()->subDays(48)->setTime(19, 0),
                'location_name' => 'India International Centre',
                'address' => '40 Max Mueller Marg',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'meeting_url' => 'https://example.com/events/digital-society',
                'banner' => null,
                'max_attendees' => 150,
                'status' => 'published',
                'categories' => [
                    'panel-discussions',
                    'seminars',
                ],
            ],

            [
                'title' => 'Global Innovation Leaders Forum',
                'slug' => 'global-innovation-leaders-forum',
                'description' => 'An international gathering of researchers, founders and institutional leaders examining the forces shaping innovation and entrepreneurship.',
                'event_type' => 'offline',
                'visibility' => 'public',
                'start_date' => now()->addDays(90)->setTime(9, 30),
                'end_date' => now()->addDays(91)->setTime(17, 0),
                'location_name' => 'Cape Town International Convention Centre',
                'address' => 'Convention Square',
                'city' => 'Cape Town',
                'state' => 'Western Cape',
                'country' => 'South Africa',
                'meeting_url' => null,
                'banner' => null,
                'max_attendees' => 500,
                'status' => 'published',
                'categories' => [
                    'conferences',
                    'networking',
                ],
            ],

            [
                'title' => 'Introduction to Evidence-Based Policy',
                'slug' => 'introduction-to-evidence-based-policy',
                'description' => 'A practical introduction to using research, data and evidence to understand policy problems and evaluate possible interventions.',
                'event_type' => 'online',
                'visibility' => 'public',
                'start_date' => now()->addDays(12)->setTime(15, 0),
                'end_date' => now()->addDays(12)->setTime(17, 0),
                'location_name' => null,
                'address' => null,
                'city' => null,
                'state' => null,
                'country' => null,
                'meeting_url' => 'https://example.com/events/evidence-policy',
                'banner' => null,
                'max_attendees' => 120,
                'status' => 'published',
                'categories' => [
                    'workshops',
                ],
            ],

            [
                'title' => 'Economic Transformation in Emerging Markets',
                'slug' => 'economic-transformation-in-emerging-markets',
                'description' => 'Researchers and policymakers examine structural transformation, investment and economic development across emerging markets.',
                'event_type' => 'offline',
                'visibility' => 'public',
                'start_date' => now()->subDays(75)->setTime(13, 0),
                'end_date' => now()->subDays(75)->setTime(17, 0),
                'location_name' => 'University of Cape Town',
                'address' => 'Rondebosch',
                'city' => 'Cape Town',
                'state' => 'Western Cape',
                'country' => 'South Africa',
                'meeting_url' => null,
                'banner' => null,
                'max_attendees' => 220,
                'status' => 'published',
                'categories' => [
                    'seminars',
                    'public-lectures',
                ],
            ],
        ];

        foreach ($events as $eventData) {
            $categorySlugs = $eventData['categories'];

            unset($eventData['categories']);

            $event = Event::updateOrCreate(
                ['slug' => $eventData['slug']],
                $eventData,
            );

            $categoryIds = collect($categorySlugs)
                ->map(fn (string $slug) => $categories->get($slug)?->id)
                ->filter()
                ->values()
                ->all();

            $event->categories()->sync($categoryIds);
        }
    }
}