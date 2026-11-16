<?php

namespace Database\Seeders;

use App\Enums\Report\ReportStatus;
use App\Enums\Report\ReportType;
use App\Models\Report\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $reports = [
            [
                'title' => 'The Future of Artificial Intelligence',
                'slug' => 'future-of-artificial-intelligence',
                'subtitle' => 'Technology, adoption, opportunities and challenges',
                'description' => 'An illustrative research report examining artificial intelligence adoption, business opportunities, emerging technologies and implementation challenges.',
                'summary' => 'This report explores how artificial intelligence is changing business operations, product development and decision-making.',
                'type' => ReportType::Research,
                'status' => ReportStatus::Published,
                'featured' => true,
                'published_at' => '2025-10-15 09:00:00',
                'report_date' => '2025-10-15',
            ],
            [
                'title' => 'The Global Software Industry Outlook',
                'slug' => 'global-software-industry-outlook',
                'subtitle' => 'An overview of software markets and industry transformation',
                'description' => 'An illustrative industry report covering software business models, cloud adoption, enterprise technology and emerging market opportunities.',
                'summary' => 'An overview of the changing software industry, including enterprise adoption, SaaS business models and technology trends.',
                'type' => ReportType::Industry,
                'status' => ReportStatus::Published,
                'featured' => true,
                'published_at' => '2025-11-10 09:00:00',
                'report_date' => '2025-11-10',
            ],
            [
                'title' => 'Consumer Digital Behaviour Report',
                'slug' => 'consumer-digital-behaviour-report',
                'subtitle' => 'Understanding digital experiences and consumer expectations',
                'description' => 'An illustrative consumer report exploring digital experiences, purchasing journeys, customer expectations and online engagement.',
                'summary' => 'A sample report structure for analysing consumer behaviour and digital engagement.',
                'type' => ReportType::Consumer,
                'status' => ReportStatus::Draft,
                'featured' => false,
                'published_at' => null,
                'report_date' => '2025-12-01',
            ],
        ];

        foreach ($reports as $data) {
            Report::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );
        }
    }
}
