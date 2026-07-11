<?php

namespace App\Support;

class AcademicsPagesContent
{
    public static function pages(): array
    {
        return [
            'academic-calendar' => [
                'eyebrow' => 'Academics',
                'subtitle' => 'Academic calendar for IDTM programmes.',
                'blocks' => [
                    ['type' => 'heading', 'text' => 'Resuming Shortly'],
                ],
            ],
            'courses-ma-mphil-phd' => [
                'eyebrow' => 'Academics',
                'subtitle' => 'Courses for MA, MPhil and PhD Programmes.',
                'blocks' => [
                    ['type' => 'heading', 'text' => 'Courses for First Semester'],
                    ['type' => 'list', 'title' => '', 'items' => [
                        'DTM 551 Theories and Concepts of Development',
                        'DTM 553 Statistics for Development Studies',
                        'DTM 555 Research Methods in Development Studies',
                        'DTM 557 Computer Applications in Development Studies',
                        'DTM 559 Philosophy of Development Studies (for MPhil and PhD candidates only)',
                    ]],
                    ['type' => 'heading', 'text' => 'Courses for Second Semester'],
                    ['type' => 'list', 'title' => '', 'items' => [
                        'DTM 562 Contemporary Issues in Development Practice',
                        'DTM 564 Analytical and Applied Techniques in Development Studies',
                        'DTM 566 Technology and Development Experience Workshop',
                        'DTM 568 Entrepreneurship Development',
                        'DTM 562–578 Specialization',
                        'DTM 699 Thesis',
                    ]],
                ],
            ],
            'ma-mphil-admissions' => [
                'eyebrow' => 'Academics',
                'subtitle' => 'MA & MPhil Admissions Details.',
                'blocks' => [
                    ['type' => 'heading', 'text' => 'Weekend MA & MPhil Programmes in Development Studies'],
                    ['type' => 'intro', 'text' => 'IDTM invites prospective applicants for its Master of Arts (MA), Master of Philosophy (MPhil) and Doctor of Philosophy (PhD) Programmes in Development Studies for the 2019 Academic Year commencing in January, 2019.'],
                    ['type' => 'list', 'title' => 'The available specializations are:', 'items' => [
                        'Development Finance',
                        'Law and Development',
                        'Community Development',
                        'Peace and Development',
                        'Technology Management',
                        'Leadership and Development',
                        'Education and Development',
                        'Project Management',
                        'Gender and Development',
                    ]],
                    ['type' => 'heading', 'text' => 'Minimum Admission Requirements'],
                    ['type' => 'intro', 'text' => 'Candidates must possess a good first degree from a recognized University with at least a Second Class (Lower Division) for MA and Second Class (Upper Division) for MPhil.'],
                    ['type' => 'heading', 'text' => 'Duration of Programme'],
                    ['type' => 'table', 'title' => 'Programme session', 'headers' => ['Programme', 'Duration', 'Session'], 'rows' => [
                        ['MA', '12 Months (1 year)', 'Weekends'],
                        ['MPhil', '24 Months (2 years)', ''],
                        ['PhD', '36 Months (3 years)', ''],
                    ]],
                    ['type' => 'heading', 'text' => 'Tuition Fee'],
                    ['type' => 'list', 'title' => '', 'items' => [
                        'MA – Six Thousand Five Hundred Ghana Cedis (GH¢7,000)',
                        'MPhil – Six Thousand Ghana Cedis (GH¢6,500) per annum',
                        'PhD – Eight Thousand Five Hundred Ghana Cedis (GH¢8,500) per annum',
                    ]],
                ],
            ],
            'mphil-development-studies' => [
                'eyebrow' => 'Academics',
                'subtitle' => 'Master of Philosophy in Development Studies.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'The Master of Philosophy (Development Studies) programme which is run as a regular programme, aims at producing high quality research scientists who have the capacity to analyze development problems and generate policy options for solving them.'],
                    ['type' => 'intro', 'text' => 'Graduates with MPhil research degrees are equipped to teach in the University and occupy high level research and policy analysis positions in Government and private sector institutions.'],
                ],
            ],
            'ma-development-studies' => [
                'eyebrow' => 'Academics',
                'subtitle' => 'Master of Arts in Development Studies.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'The Master of Arts (Development Studies) programme is a professional development programme. It seeks to train development practitioners who have the ability to conduct independent scientific research to solve development problems at the local, national and international levels for the benefit of society.'],
                ],
            ],
            'phd-development-studies' => [
                'eyebrow' => 'Academics',
                'subtitle' => 'Doctor of Philosophy in Development Studies.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'The PhD (Development Studies) programme which is run as a regular programme, aims at producing high quality research scientists who have the capacity to analyze development problems and generate policy options for solving them.'],
                    ['type' => 'intro', 'text' => 'Graduates with PhD research degrees are equipped to teach in the University and occupy high level research and policy analysis positions in Government and private sector institutions.'],
                ],
            ],
        ];
    }

    public static function libraryBlocks(): array
    {
        $path = database_path('data/library-blocks.json');

        if (! is_file($path)) {
            return [
                ['type' => 'intro', 'text' => 'The IDTM library catalogue is being updated.'],
            ];
        }

        $blocks = json_decode(file_get_contents($path), true);

        return is_array($blocks) ? $blocks : [];
    }

    public static function for(string $slug): ?array
    {
        if ($slug === 'library') {
            return [
                'eyebrow' => 'Academics',
                'subtitle' => 'Library catalogue and collections at the Institute of Development & Technology Management.',
                'blocks' => self::libraryBlocks(),
            ];
        }

        return self::pages()[$slug] ?? null;
    }
}
