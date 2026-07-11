<?php

namespace App\Support;

class AdmissionsPagesContent
{
    public static function pages(): array
    {
        return [
            'admission-form' => [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Download and submit the IDTM admission form.',
                'blocks' => [
                    ['type' => 'heading', 'text' => 'Steps to submit this form to the Registrar'],
                    ['type' => 'steps', 'title' => '', 'items' => [
                        ['title' => 'Download the form', 'text' => 'Click the download button below to get the admission forms.'],
                        ['title' => 'Print and complete', 'text' => 'Print a hard copy and fill in all required sections.'],
                        ['title' => 'Pay the application fee', 'text' => 'Pay GHS 300.00 for this form at any branch of Prudential Bank using: Account Name — Institute of Development and Technology Management; Account Number — 0202000320002; Branch — Cape Coast Branch.'],
                        ['title' => 'Submit your application', 'text' => 'Attach the pay-in slip to the completed forms and submit to: The Registrar, IDTM, P. O. Box DL 494, Adisadel – Cape Coast.'],
                    ]],
                    ['type' => 'download', 'label' => '2026 Admission Forms', 'url' => '/downloads/2026-admission-forms.pdf'],
                ],
            ],
            'admission-requirements' => [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Entry requirements for MA, MPhil, and PhD programmes at IDTM.',
                'blocks' => [
                    ['type' => 'heading', 'text' => 'MA (Development Studies)'],
                    ['type' => 'intro', 'text' => 'Entry Requirements: To be admitted to the programme for the MA (Development Studies) degree, a candidate must have a good first degree not lower than Second Class, Lower Division (2nd Lower) or its equivalent from an accredited university. In addition, the candidate must pass a selection interview and also satisfy the specific requirements for the chosen specialization.'],
                    ['type' => 'intro', 'text' => 'Requirements for Graduation: For a candidate to graduate, a minimum Cumulative Weighted Average (CWA) of 55.00 is required. All courses taken must secure a pass mark of 50%. A minimum total Credit Hours of 33 and a maximum of 39 must be achieved for graduation.'],
                    ['type' => 'list', 'title' => 'Assessment of Students Performance and Achievement', 'items' => [
                        'Continuous assessment will be based on research submissions and oral presentations. This will constitute 40% of final marks.',
                        'End of semester examinations will consist of a 3-hour examination paper per course. This will constitute 60% of final marks.',
                        'Dissertations submitted will be assessed on their own merit. A minimum pass of grade C is required.',
                    ]],
                    ['type' => 'heading', 'text' => 'MPhil Development Studies'],
                    ['type' => 'intro', 'text' => 'Entry Requirements: To be admitted to the Programme for the MPhil (Development Studies) degree, a candidate must have a good first degree not lower than Second Class, Upper Division (2nd Class Upper) or its equivalent from an accredited University. The candidate must also pass a selection interview. A choice of specialization must be related to candidate\'s background.'],
                    ['type' => 'intro', 'text' => 'Candidates with MA (Development Studies) may be admitted to the MPhil (Development Studies) programme. Such candidates will be required to take appropriate MPhil (Development Studies) courses including DTM 611: Philosophy of Development Studies and write an MPhil thesis over a period of 12 months.'],
                    ['type' => 'intro', 'text' => 'Requirements for Graduation: For a candidate to graduate with an MPhil (Development Studies) Degree, a minimum Cumulative Weighted Average (CWA) of 60.00 and a maximum of 66.00 are required. Candidates must secure a 50% pass in all MPhil (Development Studies) courses taken. A total minimum credit hours of 55 must be achieved for graduation. In addition, candidates must pass a public defense of their MPhil (Development Studies) thesis.'],
                    ['type' => 'list', 'title' => 'Assessment of Student Performance and Achievement', 'items' => [
                        'Continuous assessment makes up 40% total final marks. Research submissions and oral presentations are assessed for the purpose.',
                        'End of semester examinations make up 60% of final total marks. Three-hour exam papers are written per course.',
                        'The submitted MPhil theses must obtain a minimum pass mark of 50%.',
                        'Public defense of MPhil thesis must also obtain a minimum pass mark of 50%.',
                    ]],
                    ['type' => 'heading', 'text' => 'PhD (Development Studies)'],
                    ['type' => 'intro', 'text' => 'Entry Requirements: To be admitted into the PhD (Development Studies) programme, a candidate must have a Master of Philosophy degree in Development Studies or its equivalent from an accredited university. Candidates must also pass a selection interview. Candidates may be asked to take relevant MPhil (Development Studies) courses in year 1 of the PhD (Development Studies) programme, as appropriate.'],
                    ['type' => 'intro', 'text' => 'Requirements for Graduation: PhD (Development Studies) candidates are required to achieve a minimum total credit hours of 72 and a maximum of 81 to graduate. In addition, candidates are required to obtain a pass at a public defense of their PhD (Development Studies) thesis.'],
                    ['type' => 'list', 'title' => 'Assessment of Student Performance and Achievement', 'items' => [
                        'PhD candidates are required to make presentations at all required research seminars and obtain a minimum pass mark of 50% at each seminar.',
                        'Submitted PhD theses will be assessed on their own merit and must obtain a minimum pass mark of 50%.',
                        'A viva voce public defense of the PhD thesis must obtain a minimum pass mark of 50%.',
                    ]],
                    ['type' => 'heading', 'text' => 'Specific Entry Requirement for Specialization'],
                    ['type' => 'cards', 'title' => '', 'items' => [
                        ['title' => 'Development Finance', 'text' => 'Applicants must have a degree in Business Administration (Accounting Option), Bachelor of Commerce (B.Com) or Accounting, and other professional qualifications of relevance.'],
                        ['title' => 'Technology Management', 'text' => 'Applicants seeking to specialize in this area must have obtained a first degree in Science and Technology e.g. B.Sc. Engineering, B.Sc. Agriculture and other related programmes.'],
                        ['title' => 'Law and Development', 'text' => 'Applicants seeking to specialize in this area must have obtained a degree in Law.'],
                        ['title' => 'Higher Education and Development', 'text' => 'Applicant must possess a degree in Education.'],
                        ['title' => 'Project Management', 'text' => 'Applicants seeking to specialize in this area must have obtained a degree in Economics and related programmes.'],
                        ['title' => 'Community Development; Gender and Development; Peace and Development; Leadership and Development', 'text' => 'Applicant seeking to specialize in any of these areas must have a good first degree or its equivalent from an accredited university.'],
                    ]],
                ],
            ],
            'programmes' => [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Postgraduate programmes accredited by the Ghana Tertiary Education Commission.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'The available post-graduate programmes accredited by the Ghana Tertiary Education Commission include:'],
                    ['type' => 'list', 'title' => '', 'items' => [
                        'Master of Arts; MA (Development Studies) — Duration: 1 year',
                        'Master of Philosophy; MPhil (Development Studies) — Duration: 2 years',
                        'Doctor of Philosophy; PhD (Development Studies) — Duration: 4 years',
                    ]],
                    ['type' => 'heading', 'text' => 'Details of Academic Programmes'],
                    ['type' => 'heading', 'text' => 'MA (Development Studies)'],
                    ['type' => 'intro', 'text' => 'Aims and Objectives: The MA (Development Studies) programme is a professional development programme. It seeks to train development practitioners who have the ability to conduct independent scientific research to solve development problems at the local, national and international levels for the benefit of society.'],
                    ['type' => 'heading', 'text' => 'MPhil Development Studies'],
                    ['type' => 'intro', 'text' => 'Aims and Objectives: The MPhil (Development Studies) programme which will be run as a regular programme, aims at producing high quality research scientists who have the capacity to analyze development problems and generate policy options for solving them.'],
                    ['type' => 'intro', 'text' => 'Graduates with MPhil research degrees are equipped to teach in the University and occupy high level research and policy analysis positions in Government and private sector institutions.'],
                    ['type' => 'heading', 'text' => 'PhD (Development Studies)'],
                    ['type' => 'intro', 'text' => 'Aims and Objectives: The PhD (Development Studies) programme which will be run as a regular programme, aims at producing high quality research scientists who have the capacity to analyze development problems and generate policy options for solving them.'],
                    ['type' => 'intro', 'text' => 'Graduates with PhD research degrees are equipped to teach in the University and occupy high level research and policy analysis positions in Government and private sector institutions.'],
                ],
            ],
            'brochure' => [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Download the IDTM admission brochure.',
                'blocks' => [
                    ['type' => 'download', 'label' => '2026 Admission Brochure', 'url' => '/downloads/2026-admission-brochure.pdf'],
                ],
            ],
        ];
    }

    public static function for(string $slug): ?array
    {
        return self::pages()[$slug] ?? null;
    }
}
