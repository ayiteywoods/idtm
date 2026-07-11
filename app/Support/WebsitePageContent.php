<?php

namespace App\Support;

class WebsitePageContent
{
    public static function sectionFor(string $slug): ?string
    {
        return match ($slug) {
            'about', 'mission-vision', 'leadership', 'campus-life',
            'office-of-the-rector', 'leadership-and-staff',
            'the-rector', 'rectors-welcome', 'rector-office',
            'faculty', 'administration' => 'about',
            'admission-form', 'admission-requirements', 'programmes', 'brochure',
            'admissions', 'how-to-apply', 'fees-funding' => 'admissions',
            'academic-calendar', 'courses-ma-mphil-phd', 'library', 'ma-mphil-admissions',
            'mphil-development-studies', 'ma-development-studies', 'phd-development-studies' => 'academics',
            default => null,
        };
    }

    public static function sidebar(string $section): array
    {
        return match ($section) {
            'about' => [
                ['label' => 'About Us', 'slug' => 'about'],
                ['label' => 'Mission & Vision', 'slug' => 'mission-vision'],
                [
                    'label' => 'Office of the Rector',
                    'type' => 'dropdown',
                    'slug' => 'office-of-the-rector',
                    'items' => [
                        ['label' => 'The Rector', 'slug' => 'the-rector'],
                        ['label' => "Rector's Welcome", 'slug' => 'rectors-welcome'],
                        ['label' => 'Office Administration', 'slug' => 'rector-office'],
                    ],
                ],
                [
                    'label' => 'Leadership and Staff',
                    'type' => 'dropdown',
                    'slug' => 'leadership-and-staff',
                    'items' => [
                        ['label' => 'Leadership Team', 'slug' => 'leadership'],
                        ['label' => 'Faculty', 'slug' => 'faculty'],
                        ['label' => 'Administration', 'slug' => 'administration'],
                    ],
                ],
                ['label' => 'Campus Life', 'slug' => 'campus-life'],
            ],
            'admissions' => [
                ['label' => 'Admission Form', 'slug' => 'admission-form'],
                ['label' => 'Entry Requirement', 'slug' => 'admission-requirements'],
                ['label' => 'Programmes', 'slug' => 'programmes'],
                ['label' => 'Brochure', 'slug' => 'brochure'],
            ],
            'programmes' => [
                ['label' => 'All Programmes', 'slug' => 'programmes'],
            ],
            'academics' => [
                ['label' => 'Academic Calendar', 'slug' => 'academic-calendar'],
                ['label' => 'Courses For MA, MPhil And PhD Programmes', 'slug' => 'courses-ma-mphil-phd'],
                ['label' => 'Library', 'slug' => 'library'],
                ['label' => 'MA & MPhil Admissions Details', 'slug' => 'ma-mphil-admissions'],
                ['label' => 'MPhil Development Studies', 'slug' => 'mphil-development-studies'],
                ['label' => 'MA Development Studies', 'slug' => 'ma-development-studies'],
                ['label' => 'PhD Development Studies', 'slug' => 'phd-development-studies'],
            ],
            default => [],
        };
    }

    public static function mainNavAdmissionsItems(): array
    {
        return [
            ['label' => 'Admission Form', 'route' => route('pages.show', 'admission-form')],
            ['label' => 'Entry Requirement', 'route' => route('pages.show', 'admission-requirements')],
            ['label' => 'Programmes', 'route' => route('pages.show', 'programmes')],
            ['label' => 'Brochure', 'route' => route('pages.show', 'brochure')],
        ];
    }

    public static function admissionActiveSlugs(): array
    {
        return array_merge(self::sectionSlugs('admissions'), [
            'admissions',
            'how-to-apply',
            'fees-funding',
        ]);
    }

    public static function mainNavAboutItems(): array
    {
        return [
            ['label' => 'Office of the Rector', 'route' => route('pages.show', 'office-of-the-rector')],
            ['label' => 'Leadership and Staff', 'route' => route('pages.show', 'leadership-and-staff')],
        ];
    }

    public static function flatNavItems(string $section): array
    {
        return collect(self::sidebar($section))
            ->flatMap(function (array $item) {
                if (($item['type'] ?? 'link') === 'dropdown') {
                    return collect($item['items'])->map(fn (array $subItem) => [
                        'label' => $subItem['label'],
                        'route' => route('pages.show', $subItem['slug']),
                    ]);
                }

                return [[
                    'label' => $item['label'],
                    'route' => route('pages.show', $item['slug']),
                ]];
            })
            ->values()
            ->all();
    }

    public static function sectionSlugs(string $section): array
    {
        return collect(self::sidebar($section))
            ->flatMap(function (array $item) {
                if (($item['type'] ?? 'link') === 'dropdown') {
                    $slugs = collect($item['items'])->pluck('slug');

                    if (isset($item['slug'])) {
                        $slugs->prepend($item['slug']);
                    }

                    return $slugs;
                }

                return [$item['slug'] ?? null];
            })
            ->filter()
            ->values()
            ->all();
    }

    public static function for(string $slug): ?array
    {
        return self::pages()[$slug] ?? null;
    }

    public static function pages(): array
    {
        return [
            'about' => [
                'eyebrow' => 'About Us',
                'subtitle' => 'Educating Ghana\'s next generation of development and technology leaders since our founding.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'The Institute of Development & Technology Management (IDTM) is a premier institution committed to excellence in development studies and technology management across Ghana and West Africa. We deliver rigorous MBA and executive programmes designed for working professionals who aspire to lead organisations, drive policy change, and create lasting impact.'],
                    ['type' => 'cards', 'title' => 'What defines IDTM', 'items' => [
                        ['title' => 'Ghana-rooted, globally minded', 'text' => 'Our curriculum blends international business standards with the realities of African markets, policy, and enterprise.'],
                        ['title' => 'Practitioner-led teaching', 'text' => 'Faculty combine academic rigour with real-world experience across finance, entrepreneurship, and management.'],
                        ['title' => 'Career-focused outcomes', 'text' => 'Graduates advance into leadership roles, launch ventures, and contribute to Ghana\'s economic growth.'],
                        ['title' => 'Supportive community', 'text' => 'From orientation to alumni networks, students are supported at every stage of their academic journey.'],
                    ]],
                    ['type' => 'stats', 'items' => [
                        ['value' => '15+', 'label' => 'Years of Excellence'],
                        ['value' => '2', 'label' => 'MBA Specializations'],
                        ['value' => '100%', 'label' => 'Ghana-based Faculty'],
                        ['value' => 'Accra', 'label' => 'Campus Location'],
                    ]],
                ],
            ],
            'mission-vision' => [
                'eyebrow' => 'Mission & Vision',
                'subtitle' => 'Our purpose and the future we are building for development and technology education in Ghana.',
                'blocks' => [
                    ['type' => 'split', 'items' => [
                        ['title' => 'Our Mission', 'text' => 'To develop ethical, innovative leaders in development and technology management through world-class education, research, and community engagement rooted in Ghanaian and African contexts.'],
                        ['title' => 'Our Vision', 'text' => 'To be West Africa\'s most trusted institution for development studies and technology management — recognised for academic excellence, graduate success, and contribution to national development.'],
                    ]],
                    ['type' => 'list', 'title' => 'Our Core Values', 'items' => [
                        'Excellence in teaching, learning, and scholarship',
                        'Integrity and ethical leadership in all endeavours',
                        'Innovation and entrepreneurship for national development',
                        'Inclusivity and respect for diverse perspectives',
                        'Community engagement and social responsibility',
                    ]],
                ],
            ],
            'office-of-the-rector' => [
                'eyebrow' => 'Office of the Rector',
                'subtitle' => 'Professor John Andoh Micah — Rector of the Institute of Development & Technology Management.',
                'blocks' => [
                    ['type' => 'rector-profile', 'image' => '/images/hero/slide-2.jpg', 'alt' => 'Professor John Andoh Micah, Rector of IDTM', 'name' => 'Professor John Andoh Micah', 'role' => 'Rector', 'intro' => 'Welcome to the Institute of Development & Technology Management. Whether you are a prospective student, faculty colleague, partner institution, or member of our alumni community, I am delighted that you are exploring what IDTM has to offer.', 'message' => [
                        'IDTM exists to educate Ghana\'s next generation of development and technology leaders.',
                        'Our programmes combine academic rigour with practical insight drawn from Ghanaian and African contexts.',
                        'We invest in faculty excellence, student support, and partnerships that open doors for our graduates.',
                        'I invite you to learn more about our programmes, visit our campus in Accra, and join a community committed to knowledge and excellence.',
                    ]],
                ],
            ],
            'leadership-and-staff' => [
                'eyebrow' => 'Leadership and Staff',
                'subtitle' => 'Administration, governing council, and teaching staff of the Institute of Development & Technology Management.',
                'blocks' => [
                    ['type' => 'heading', 'text' => 'Administration of the Institute'],
                    ['type' => 'list', 'title' => "The Institute's Principal officers are:", 'items' => [
                        'Professor John A. Micah (PhD) – Rector',
                        'Professor L. K. Sam-Amoah (PhD) – Director of Programmes and Chairman of Council',
                        'Professor Philip Bondzi-Simpson (SJD) – Legal Counsel',
                        'Kwabena Owusu (CA) – Accountant',
                        'Mildred Asmah (MED) – Registrar',
                    ]],
                    ['type' => 'intro', 'text' => 'These constitute the IDTM Governing Council. The Academic Board of the Institute has a wider representation of all its facets. The Academic Board and the Management Board are responsible for the academic and general administration of the Institute.'],
                    ['type' => 'heading', 'text' => 'Teaching Staff and their Qualifications'],
                    ['type' => 'table', 'title' => '', 'headers' => ['Name', 'Qualification', 'Position'], 'rows' => [
                        ['John Andoh Micah', 'PhD (Economics) (Cape Coast), 1990', 'Professor'],
                        ['Beatrice Adwoa Okyere', 'PhD (Edu. Psychology) (Ohio State), 1990', 'Associate Professor'],
                        ['Livingstone Sam-Amoah', 'PhD (Agricultural Engineering) (New Castle), 2001', 'Professor'],
                        ['Philip Ebo Bondzi-Simpson', 'SJD (Law) (Toronto), 1991', 'Professor'],
                        ['Stephen Adei', 'PhD (Economics) (Sydney), 1981', 'Professor'],
                        ['Kwaku Adutwum Boakye', 'PhD (Tourism) (Cape Coast), 2009', 'Associate Professor'],
                        ['Joseph Boateng Agyenim', 'PhD (Environmental Studies) (Amsterdam), 2011', 'Senior Lecturer'],
                        ['Beatrice Esi Mensah', 'PhD (Development Studies) (Cape Coast), 2004', 'Senior Lecturer'],
                        ['Samuel Kobina Annim', 'PhD (Economics) (Manchester), 2010', 'Associate Professor'],
                        ['Ebenezer Anuwa–Amarh', 'MPhil (Development Studies) (Cape Coast), 1998', 'Senior Lecturer'],
                        ['Edwin Amonoo', 'PhD (Economics) (Rotterdam)', 'Senior Lecturer'],
                        ['Kenneth Shelton Aikins', 'PhD (Political Science) (Kansas), 2011', 'Senior Lecturer'],
                        ['Eric Nyarko-Sampson', 'PhD (Education) (Ilorin), 2013', 'Associate Professor'],
                        ['Fredrick Koomson', 'PhD (Development Studies) (Cape Coast), 2015', 'Senior Research Fellow'],
                        ['Grace Vanderpuije', 'PhD (Plant Pathology) (Reading), 2006', 'Associate Professor'],
                        ['Rebecca Owusu', 'PhD (Agric. Econs) (UWA Australia), 2017', 'Lecturer'],
                        ['Kwadwo Tuffour', 'PhD (Development Studies) (Cape Coast), 2014', 'Senior Lecturer'],
                        ['Raymond Kofinti', 'PhD (Economics) (Cape Coast), 2020', 'Lecturer'],
                        ['Kyeremeh Tawiah Dabone', 'PhD (Guidance & Counselling) (Cape Coast), 2018', 'Lecturer'],
                        ['David Baba Sempah', 'PhD (Development Studies) (Cape Coast), 2021', 'Lecturer'],
                        ['Jeffrey Kenneth Baiden', 'PhD (Development Studies) (Cape Coast), 2023', 'Lecturer'],
                        ['John Panyin Abban', 'PhD (Development Studies) (Cape Coast), 2023', 'Lecturer'],
                    ]],
                ],
            ],
            'leadership' => [
                'eyebrow' => 'Leadership and Staff',
                'subtitle' => 'Meet the leadership team guiding the Institute of Development & Technology Management.',
                'blocks' => [
                    ['type' => 'people', 'items' => [
                        ['name' => 'Prof. Kwame Asante', 'role' => 'Director', 'bio' => 'Over 25 years of experience in higher education leadership and development policy across Ghana and the ECOWAS region.'],
                        ['name' => 'Dr. Ama Osei-Bonsu', 'role' => 'Dean of Programmes', 'bio' => 'Former corporate executive and academic specialising in technology management, innovation, and executive education.'],
                        ['name' => 'Mr. Daniel Mensah', 'role' => 'Registrar', 'bio' => 'Leads student records, admissions operations, and institutional compliance for the university.'],
                        ['name' => 'Mrs. Abena Kwarteng', 'role' => 'Director of Admissions', 'bio' => 'Guides prospective students through programme selection, application, and enrolment processes.'],
                    ]],
                ],
            ],
            'the-rector' => [
                'eyebrow' => 'Office of the Rector',
                'subtitle' => 'Professor John Andoh Micah — Rector of the Institute of Development & Technology Management.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'Professor John Andoh Micah serves as Rector of the Institute of Development & Technology Management, providing strategic leadership for academic excellence, institutional growth, and community engagement across Ghana and West Africa.'],
                    ['type' => 'cards', 'title' => 'Profile', 'items' => [
                        ['title' => 'Academic Leadership', 'text' => 'A distinguished scholar and administrator with extensive experience in development studies, technology management, and higher education governance.'],
                        ['title' => 'Institutional Vision', 'text' => 'Leads IDTM\'s mission to develop ethical, innovative leaders equipped to drive policy change and enterprise growth.'],
                        ['title' => 'Community Engagement', 'text' => 'Champions partnerships with industry, government, and alumni to strengthen graduate outcomes and national development impact.'],
                    ]],
                ],
            ],
            'rectors-welcome' => [
                'eyebrow' => 'Office of the Rector',
                'subtitle' => 'A welcome message from the Rector to prospective students, partners, and the IDTM community.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'Welcome to the Institute of Development & Technology Management. Whether you are a prospective student, faculty colleague, partner institution, or member of our alumni community, I am delighted that you are exploring what IDTM has to offer.'],
                    ['type' => 'list', 'title' => 'A Message from the Rector', 'items' => [
                        'IDTM exists to educate Ghana\'s next generation of development and technology leaders.',
                        'Our programmes combine academic rigour with practical insight drawn from Ghanaian and African contexts.',
                        'We invest in faculty excellence, student support, and partnerships that open doors for our graduates.',
                        'I invite you to learn more about our programmes, visit our campus in Accra, and join a community committed to knowledge and excellence.',
                    ]],
                ],
            ],
            'rector-office' => [
                'eyebrow' => 'Office of the Rector',
                'subtitle' => 'Contact and administrative support for the Office of the Rector.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'The Office of the Rector coordinates institutional strategy, ceremonial functions, executive communications, and liaison with governing bodies, partners, and stakeholders.'],
                    ['type' => 'cards', 'title' => 'Office Services', 'items' => [
                        ['title' => 'Executive Correspondence', 'text' => 'Handles official communications, invitations, and institutional representation requests.'],
                        ['title' => 'Strategic Planning', 'text' => 'Supports the Rector in academic planning, accreditation, and institutional development initiatives.'],
                        ['title' => 'Protocol & Events', 'text' => 'Coordinates graduation congregations, distinguished lectures, and high-level university events.'],
                    ]],
                    ['type' => 'cta', 'text' => 'For enquiries to the Office of the Rector, contact our main campus office.', 'primary' => ['label' => 'Contact Us', 'route' => 'contact']],
                ],
            ],
            'faculty' => [
                'eyebrow' => 'Leadership and Staff',
                'subtitle' => 'Academic faculty delivering IDTM\'s postgraduate and executive programmes.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'IDTM faculty combine scholarly expertise with professional practice across development policy, technology management, finance, entrepreneurship, and analytics. They mentor students through coursework, research supervision, and industry engagement.'],
                    ['type' => 'people', 'items' => [
                        ['name' => 'Dr. Kofi Mensah', 'role' => 'Senior Lecturer — Business Intelligence', 'bio' => 'Specialises in data analytics, decision science, and enterprise intelligence for African markets.'],
                        ['name' => 'Dr. Efua Boateng', 'role' => 'Senior Lecturer — Entrepreneurship', 'bio' => 'Research focus on venture creation, innovation ecosystems, and SME growth in Ghana.'],
                        ['name' => 'Mr. Samuel Adjei', 'role' => 'Lecturer — Technology Management', 'bio' => 'Brings corporate technology leadership experience into the classroom and executive seminars.'],
                    ]],
                ],
            ],
            'administration' => [
                'eyebrow' => 'Leadership and Staff',
                'subtitle' => 'Professional staff supporting admissions, student services, finance, and campus operations.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'Behind every successful cohort is a dedicated administrative team ensuring smooth admissions, student records, finance operations, library services, and campus support for students and faculty.'],
                    ['type' => 'people', 'items' => [
                        ['name' => 'Mr. Daniel Mensah', 'role' => 'Registrar', 'bio' => 'Oversees student records, examinations, and institutional compliance.'],
                        ['name' => 'Mrs. Abena Kwarteng', 'role' => 'Director of Admissions', 'bio' => 'Leads recruitment, application processing, and enrolment for all programmes.'],
                        ['name' => 'Ms. Grace Owusu', 'role' => 'Finance Officer', 'bio' => 'Manages tuition billing, payment plans, and student wallet operations.'],
                        ['name' => 'Mr. Isaac Tetteh', 'role' => 'IT & Systems Administrator', 'bio' => 'Supports the student portal, faculty systems, and campus technology infrastructure.'],
                    ]],
                ],
            ],
            'campus-life' => [
                'eyebrow' => 'Campus Life',
                'subtitle' => 'A vibrant learning community in the heart of Accra, Ghana.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'Life at IDTM extends beyond the classroom. Students engage in seminars, leadership workshops, networking events, and collaborative projects that build lasting professional relationships across Ghana\'s development and technology sectors.'],
                    ['type' => 'cards', 'title' => 'Student Experience', 'items' => [
                        ['title' => 'Learning Resources', 'text' => 'Access our physical and digital library, research databases, and study facilities on campus.'],
                        ['title' => 'Networking Events', 'text' => 'Regular industry talks, alumni panels, and business forums connect students with leaders.'],
                        ['title' => 'Student Associations', 'text' => 'Join peer groups focused on entrepreneurship, finance, and professional development.'],
                        ['title' => 'Accra Location', 'text' => 'Study in Ghana\'s capital with easy access to business districts, embassies, and corporate hubs.'],
                    ]],
                ],
            ],
            'admission-form' => AdmissionsPagesContent::for('admission-form') ?? [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Download and submit the IDTM admission form.',
                'blocks' => [],
            ],
            'brochure' => AdmissionsPagesContent::for('brochure') ?? [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Download the IDTM admission brochure.',
                'blocks' => [],
            ],
            'admissions' => [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Join the next cohort of leaders at the Institute of Development & Technology Management, Ghana.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'Our admissions team welcomes applications from qualified professionals across Ghana and internationally. We seek motivated candidates with academic preparation, work experience, and the drive to make a meaningful impact in business and society.'],
                    ['type' => 'steps', 'title' => 'Application Process', 'items' => [
                        ['title' => 'Explore Programmes', 'text' => 'Review our MBA specializations and select the pathway that matches your career goals.'],
                        ['title' => 'Check Requirements', 'text' => 'Confirm you meet academic and professional entry criteria for your chosen programme.'],
                        ['title' => 'Submit Application', 'text' => 'Complete the online form and upload all required supporting documents.'],
                        ['title' => 'Receive Decision', 'text' => 'Admissions committee reviews your application and communicates the outcome.'],
                    ]],
                    ['type' => 'cta', 'title' => 'Ready to apply?', 'text' => 'Start your application or speak with our admissions team today.', 'primary' => ['label' => 'How to Apply', 'route' => 'pages.show', 'params' => ['how-to-apply']], 'secondary' => ['label' => 'Contact Admissions', 'route' => 'contact']],
                ],
            ],
            'admission-requirements' => AdmissionsPagesContent::for('admission-requirements') ?? [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Entry requirements for IDTM programmes.',
                'blocks' => [],
            ],
            'how-to-apply' => [
                'eyebrow' => 'How to Apply',
                'subtitle' => 'Step-by-step guide to submitting your application to IDTM.',
                'blocks' => [
                    ['type' => 'steps', 'items' => [
                        ['title' => 'Create your applicant profile', 'text' => 'Contact admissions to receive application access or submit an enquiry through our contact form.'],
                        ['title' => 'Select your programme', 'text' => 'Choose your MBA specialization and preferred intake cohort (e.g. April 2026).'],
                        ['title' => 'Upload documents', 'text' => 'Submit transcripts, CV, ID, and reference letters in PDF format.'],
                        ['title' => 'Pay application fee', 'text' => 'Complete the non-refundable application fee via bank transfer or approved payment channel.'],
                        ['title' => 'Attend interview (if required)', 'text' => 'Shortlisted candidates may be invited for an admissions interview in Accra or online.'],
                        ['title' => 'Accept your offer', 'text' => 'Upon admission, accept your offer, pay enrolment fees, and access the student portal.'],
                    ]],
                    ['type' => 'cta', 'title' => 'Need help with your application?', 'text' => 'Our admissions officers are available to guide you through every step.', 'primary' => ['label' => 'Contact Admissions', 'route' => 'contact']],
                ],
            ],
            'fees-funding' => [
                'eyebrow' => 'Fees & Funding',
                'subtitle' => 'Transparent programme fees and flexible payment options for students in Ghana.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'Programme fees are set per academic year and cohort. IDTM offers structured payment plans to help students manage tuition alongside professional commitments.'],
                    ['type' => 'table', 'title' => 'Indicative Programme Fees', 'headers' => ['Programme', 'Total Fees (GHS)', 'Payment Plan'], 'rows' => [
                        ['Dual MBA', '37,800.00', 'Installments via student portal'],
                    ]],
                    ['type' => 'cards', 'title' => 'Funding Options', 'items' => [
                        ['title' => 'Employer Sponsorship', 'text' => 'Many students receive partial or full sponsorship from their employers.'],
                        ['title' => 'Installment Plans', 'text' => 'Spread tuition payments across the programme duration through the student wallet.'],
                        ['title' => 'Scholarships', 'text' => 'Merit-based scholarships may be available for outstanding applicants — enquire with admissions.'],
                    ]],
                ],
            ],
            'programmes' => AdmissionsPagesContent::for('programmes') ?? [
                'eyebrow' => 'Admissions',
                'subtitle' => 'Postgraduate programmes at IDTM.',
                'blocks' => [],
            ],
            'academic-calendar' => AcademicsPagesContent::for('academic-calendar') ?? [
                'eyebrow' => 'Academics',
                'subtitle' => 'Academic calendar for IDTM programmes.',
                'blocks' => [
                    ['type' => 'heading', 'text' => 'Resuming Shortly'],
                ],
            ],
            'courses-ma-mphil-phd' => AcademicsPagesContent::for('courses-ma-mphil-phd') ?? [
                'eyebrow' => 'Academics',
                'subtitle' => 'Course offerings across MA, MPhil, and PhD Development Studies programmes.',
                'blocks' => [],
            ],
            'ma-mphil-admissions' => AcademicsPagesContent::for('ma-mphil-admissions') ?? [
                'eyebrow' => 'Academics',
                'subtitle' => 'Admission requirements and application guidance for MA and MPhil programmes.',
                'blocks' => [],
            ],
            'ma-development-studies' => AcademicsPagesContent::for('ma-development-studies') ?? [
                'eyebrow' => 'Academics',
                'subtitle' => 'Master of Arts in Development Studies.',
                'blocks' => [],
            ],
            'mphil-development-studies' => AcademicsPagesContent::for('mphil-development-studies') ?? [
                'eyebrow' => 'Academics',
                'subtitle' => 'Master of Philosophy in Development Studies.',
                'blocks' => [],
            ],
            'phd-development-studies' => AcademicsPagesContent::for('phd-development-studies') ?? [
                'eyebrow' => 'Academics',
                'subtitle' => 'Doctor of Philosophy in Development Studies.',
                'blocks' => [],
            ],
            'alumni' => [
                'eyebrow' => 'Alumni',
                'subtitle' => 'Stay connected with the IDTM alumni network.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'Our alumni community spans corporate leadership, entrepreneurship, public service, and consulting across Ghana and the diaspora. Stay engaged through events, mentorship programmes, and career resources.'],
                    ['type' => 'cards', 'items' => [
                        ['title' => 'Alumni Events', 'text' => 'Annual reunions, networking dinners, and professional development workshops in Accra.'],
                        ['title' => 'Mentorship', 'text' => 'Connect with experienced graduates who guide current students and recent alumni.'],
                        ['title' => 'Career Network', 'text' => 'Access job postings, referrals, and opportunities within the IDTM community.'],
                    ]],
                ],
            ],
            'careers' => [
                'eyebrow' => 'Careers',
                'subtitle' => 'Join the IDTM team.',
                'blocks' => [
                    ['type' => 'intro', 'text' => 'We seek passionate academics and professionals to join the Institute of Development & Technology Management. Open positions are listed below — send your CV to our HR team for consideration.'],
                    ['type' => 'list', 'title' => 'Current Opportunities', 'items' => [
                        'Lecturer — Business Intelligence & Analytics',
                        'Lecturer — Entrepreneurship & Innovation',
                        'Admissions Officer',
                        'Library & Research Assistant',
                    ]],
                    ['type' => 'cta', 'text' => 'Send your application to careers@idtm.edu.gh', 'primary' => ['label' => 'Contact HR', 'route' => 'contact']],
                ],
            ],
            'library' => AcademicsPagesContent::for('library') ?? [
                'eyebrow' => 'Academics',
                'subtitle' => 'Library catalogue and collections at IDTM.',
                'blocks' => [],
            ],
        ];
    }
}
