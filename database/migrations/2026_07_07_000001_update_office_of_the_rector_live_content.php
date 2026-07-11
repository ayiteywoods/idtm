<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $blocks = [
            [
                'type' => 'rector-profile',
                'image' => '/images/hero/slide-2.jpg',
                'alt' => 'Professor John Andoh Micah, Rector of IDTM',
                'name' => 'PROFESSOR JOHN ANDOH MICAH',
                'role' => 'Rector',
                'intro' => 'Welcome to the Institute of Development and Technology Management (IDTM) located in the ancient capital city of Cape Coast, Ghana. The Institute continues to forge links with Ghanaian and foreign institutions in order to broaden the frontiers of knowledge and development experience of stakeholders.',
                'message' => [
                    'Professor John Micah, PhD (Econs, Cape Coast); M Soc. Sc. (The Hague); Bsc. Hons (Agric, Ghana) is currently the Rector of the Institute of Development and Technology Management (IDTM), Cape Coast.',
                    'Prior to this, he has been, among others: Associate Research Professor, Institute for Development Studies (IDS), University of Cape Coast (UCC) (2003–2007); Director of IDS, UCC (1996–2000); Head, Department of Agricultural Economics and Extension, UCC (1990–1994); Visiting Researcher and Professor, Department of Peace and Development Research, University of Goteborg and Institute of Physical Resource Theory, Chalmers University of Technology, Goteborg, Sweden (2001–2002); Research Consultant, World Vision Ghana (1994–2000); Member, Management Board of Science and Technology Policy Research Institute (STEPRI) (1995–1998); Member, National Steering Committee, Carnegie Project on Science and Technology Studies in Ghana (1996–2000); Chairman of Chaplaincy Board, UCC (1993–2000); Member, Board of Directors, Ghana Civil Aviation Authority (GCAA) Accra (1987); Rural Development Consultant, BSRRD, Methodist Church Ghana (1988–1990).',
                    'While at UCC, Professor Micah developed a number of innovative academic programmes including: MPhil Degree Programme in Agricultural Economics; BSc. Agricultural Extension Programme to upgrade the knowledge and skills of field extension staff of the Ministry of Food and Agriculture; Diploma Programme in Labour Studies for Trade Unionists; Doctor of Philosophy (PhD) Programme in Development Studies; and Doctor of Philosophy (PhD) programme in Agricultural Economics.',
                    'Professor Micah serves the University of Ghana (UG), the Kwame Nkrumah University of Science and Technology (KNUST) and University for Development Studies (UDS) as External Examiner and External Assessor. He has also been a member of the Ghana Science Association (GSA) (1980–2017); Society for International Development (SID); the Association of University Technology Managers (AUTM); and the Governing Council of Trinity Theological Seminary, Legon (2014–2018).',
                    'Professor Micah’s professional and consultancy experience is broad, varied and spans over thirty years. He has conducted assignments for the World Vision; Care International; Ghana Home Science Association; International Development Research Centre (IDRC), Canada; US Peace Corps (Ghana); National Accreditation Board (NAB); Christian Teachers Fellowship (CTF) Ghana; State Housing Company; National Agricultural Research Project (NARP); Canadian International Development Agency (CIDA); the Food and Agriculture Organization (FAO); Ghana Investment Promotion Centre (GIPC); Ministries of Finance and Economic Planning, Health, Trade and Industry, Mobilisation and Social Welfare.',
                    'Professor Micah has authored and co-authored several books, scientific papers and technical reports.',
                    'Prof. Micah is also the recipient of a number of awards, including: the 1991 Silver Award of the Ghana Academy of Arts and Sciences (GAAS); the Chaplaincy Award of the University of Cape Coast (UCC) (1998); and a Volunteer Service Award of the Association of University Technology Managers (AUTM) (2012).',
                    'As a family man, Professor Micah is married to Gladys and they have three adult children.',
                ],
            ],
            [
                'type' => 'list',
                'title' => 'Why opt for IDTM',
                'items' => [
                    'Tuition by Experienced Professors',
                    'Effective Interactive Teaching',
                    'Excellent Thesis Supervision',
                    'Flexible Study Options',
                    'Enhanced Career Prospects',
                ],
            ],
            [
                'type' => 'download',
                'label' => 'Admission Brochure',
                'url' => '/downloads/2026-admission-brochure.pdf',
            ],
            [
                'type' => 'cta',
                'title' => 'Contact the Institute',
                'text' => 'For enquiries to the Office of the Rector, contact our main campus office.',
                'primary' => ['label' => 'Contact Us', 'route' => 'contact'],
            ],
        ];

        DB::table('site_pages')
            ->where('slug', 'office-of-the-rector')
            ->update([
                'eyebrow' => 'Office of the Rector',
                'subtitle' => 'Professor John Andoh Micah — Rector of the Institute of Development & Technology Management.',
                'content' => $blocks[0]['intro'],
                'blocks' => json_encode($blocks),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('site_pages')
            ->where('slug', 'office-of-the-rector')
            ->update([
                'subtitle' => 'Professor John Andoh Micah — Rector of the Institute of Development & Technology Management.',
                'content' => '',
                'blocks' => json_encode([
                    [
                        'type' => 'rector-profile',
                        'image' => '/images/hero/slide-2.jpg',
                        'alt' => 'Professor John Andoh Micah, Rector of IDTM',
                        'name' => 'Professor John Andoh Micah',
                        'role' => 'Rector',
                        'intro' => 'Welcome to the Institute of Development & Technology Management. Whether you are a prospective student, faculty colleague, partner institution, or member of our alumni community, I am delighted that you are exploring what IDTM has to offer.',
                        'message' => [
                            'IDTM exists to educate Ghana\'s next generation of development and technology leaders.',
                            'Our programmes combine academic rigour with practical insight drawn from Ghanaian and African contexts.',
                            'We invest in faculty excellence, student support, and partnerships that open doors for our graduates.',
                            'I invite you to learn more about our programmes, visit our campus in Accra, and join a community committed to knowledge and excellence.',
                        ],
                    ],
                ]),
                'updated_at' => now(),
            ]);
    }
};

