<?php

namespace Database\Seeders;

use App\Enums\Magazine\MagazineArticleStatus;
use App\Enums\Magazine\MagazineArticleType;
use App\Models\Magazine\MagazineIssue;
use Illuminate\Database\Seeder;

class MagazineArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            'the-future-of-innovation' => [
                [
                    'title' => 'How Technology Is Transforming Modern Industries',
                    'slug' => 'how-technology-is-transforming-modern-industries',
                    'subtitle' => 'Understanding the technologies reshaping modern businesses.',
                    'excerpt' => 'An exploration of the technologies changing business operations, productivity and the modern workplace.',
                    'content' => <<<'HTML'
<h2>Technology and the changing business landscape</h2>
<p>Technology continues to reshape how organizations operate, communicate and deliver value. Businesses are adopting digital systems to improve efficiency, make informed decisions and respond to changing customer expectations.</p>

<h2>The role of innovation</h2>
<p>Innovation is not limited to introducing new products. It also includes improving processes, rethinking established practices and creating better experiences for customers and employees.</p>

<h2>Looking ahead</h2>
<p>Organizations that understand emerging technologies and evaluate their practical applications will be better prepared to adapt to future changes.</p>
HTML,
                    'type' => MagazineArticleType::Article,
                    'position' => 1,
                    'featured' => true,
                    'status' => MagazineArticleStatus::Published,
                    'published_at' => now()->subMonths(3),
                ],
                [
                    'title' => 'Building a Business Around a New Idea',
                    'slug' => 'building-a-business-around-a-new-idea',
                    'subtitle' => 'From identifying a problem to developing a sustainable business.',
                    'excerpt' => 'A practical exploration of turning an early-stage idea into a business with a clear purpose and direction.',
                    'content' => <<<'HTML'
<h2>Start with a real problem</h2>
<p>Successful businesses often begin by identifying a problem that people or organizations genuinely need to solve.</p>

<h2>Validate the idea</h2>
<p>Research, customer conversations and small experiments can help determine whether an idea addresses a meaningful need.</p>

<h2>Build and improve</h2>
<p>A business develops through continuous learning, customer feedback and improvements to its products and services.</p>
HTML,
                    'type' => MagazineArticleType::Feature,
                    'position' => 2,
                    'featured' => false,
                    'status' => MagazineArticleStatus::Published,
                    'published_at' => now()->subMonths(3)->addDays(4),
                ],
            ],

            'leadership-and-new-perspectives' => [
                [
                    'title' => 'What Makes Leadership Effective?',
                    'slug' => 'what-makes-leadership-effective',
                    'subtitle' => 'Communication, responsibility and the ability to learn.',
                    'excerpt' => 'Examining the practices that help leaders guide teams through changing circumstances.',
                    'content' => <<<'HTML'
<h2>Leadership is a responsibility</h2>
<p>Effective leadership involves setting direction, communicating clearly and creating an environment where people can contribute.</p>

<h2>Listening and learning</h2>
<p>Leaders need to understand the perspectives of their teams, respond to feedback and remain open to new information.</p>

<h2>Creating long-term value</h2>
<p>Leadership is not only about immediate results. It also involves developing people and building systems that support sustainable progress.</p>
HTML,
                    'type' => MagazineArticleType::Opinion,
                    'position' => 1,
                    'featured' => true,
                    'status' => MagazineArticleStatus::Published,
                    'published_at' => now()->subMonths(2),
                ],
                [
                    'title' => 'A Conversation About the Future of Work',
                    'slug' => 'a-conversation-about-the-future-of-work',
                    'subtitle' => 'How organizations and professionals are adapting.',
                    'excerpt' => 'A sample interview exploring changing workplaces, skills and professional development.',
                    'content' => <<<'HTML'
<h2>Introduction</h2>
<p>The workplace is changing as technology, business models and professional expectations evolve.</p>

<h2>What changes are organizations seeing?</h2>
<p>Organizations are rethinking how teams collaborate, how skills are developed and how technology supports everyday work.</p>

<h2>What should professionals focus on?</h2>
<p>Continuous learning, practical experience and the ability to adapt remain important across many industries.</p>
HTML,
                    'type' => MagazineArticleType::Interview,
                    'position' => 2,
                    'featured' => false,
                    'status' => MagazineArticleStatus::Published,
                    'published_at' => now()->subMonths(2)->addDays(5),
                ],
            ],

            'the-art-of-creative-expression' => [
                [
                    'title' => 'The Changing Language of Modern Design',
                    'slug' => 'the-changing-language-of-modern-design',
                    'subtitle' => 'How visual communication responds to culture and technology.',
                    'excerpt' => 'An introduction to the changing practices and influences shaping contemporary design.',
                    'content' => <<<'HTML'
<h2>Design communicates ideas</h2>
<p>Design gives form to ideas and helps people understand information, products and experiences.</p>

<h2>Culture and technology</h2>
<p>Contemporary design is influenced by cultural movements, changing tools and the ways people interact with digital and physical environments.</p>

<h2>The importance of clarity</h2>
<p>Good visual communication considers both expression and usability. A design should communicate its intended message clearly.</p>
HTML,
                    'type' => MagazineArticleType::Feature,
                    'position' => 1,
                    'featured' => true,
                    'status' => MagazineArticleStatus::Published,
                    'published_at' => now()->subMonths(2),
                ],
            ],

            'building-sustainable-businesses' => [
                [
                    'title' => 'Understanding Sustainable Business Models',
                    'slug' => 'understanding-sustainable-business-models',
                    'subtitle' => 'Creating value beyond short-term growth.',
                    'excerpt' => 'An introduction to business models that balance customer value, operational efficiency and long-term sustainability.',
                    'content' => <<<'HTML'
<h2>What makes a business model sustainable?</h2>
<p>A sustainable business model creates value for customers while maintaining a viable operating and financial structure.</p>

<h2>Understand the customer</h2>
<p>Organizations need to understand customer needs and build products and services that address them.</p>

<h2>Think long term</h2>
<p>Long-term sustainability requires thoughtful resource allocation, responsible decisions and continuous improvement.</p>
HTML,
                    'type' => MagazineArticleType::Article,
                    'position' => 1,
                    'featured' => true,
                    'status' => MagazineArticleStatus::Published,
                    'published_at' => now()->subMonth(),
                ],
            ],

            'research-and-the-changing-world' => [
                [
                    'title' => 'Why Research Matters in Modern Decision-Making',
                    'slug' => 'why-research-matters-in-modern-decision-making',
                    'subtitle' => 'Using evidence to understand complex questions.',
                    'excerpt' => 'An introduction to the role of research in understanding problems and making informed decisions.',
                    'content' => <<<'HTML'
<h2>Understanding before deciding</h2>
<p>Research provides a structured way to investigate questions, examine evidence and understand different perspectives.</p>

<h2>Evidence and interpretation</h2>
<p>Good research distinguishes collected evidence from assumptions and interpretations.</p>

<h2>Building knowledge</h2>
<p>Well-documented research makes knowledge easier to evaluate, communicate and develop further.</p>
HTML,
                    'type' => MagazineArticleType::Article,
                    'position' => 1,
                    'featured' => false,
                    'status' => MagazineArticleStatus::Draft,
                    'published_at' => null,
                ],
            ],
        ];

        foreach ($articles as $issueSlug => $issueArticles) {
            $issue = MagazineIssue::query()
                ->where('slug', $issueSlug)
                ->firstOrFail();

            foreach ($issueArticles as $data) {
                $issue->articles()->updateOrCreate(
                    ['slug' => $data['slug']],
                    $data,
                );
            }
        }
    }
}
