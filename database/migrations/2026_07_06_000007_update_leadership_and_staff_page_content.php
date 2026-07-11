<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $blocks = [
            [
                'type' => 'heading',
                'text' => 'Administration of the Institute',
            ],
            [
                'type' => 'list',
                'title' => "The Institute's Principal officers are:",
                'items' => [
                    'Professor John A. Micah (PhD) – Rector',
                    'Professor L. K. Sam-Amoah (PhD) – Director of Programmes and Chairman of Council',
                    'Professor Philip Bondzi-Simpson (SJD) – Legal Counsel',
                    'Kwabena Owusu (CA) – Accountant',
                    'Mildred Asmah (MED) – Registrar',
                ],
            ],
            [
                'type' => 'intro',
                'text' => 'These constitute the IDTM Governing Council. The Academic Board of the Institute has a wider representation of all its facets. The Academic Board and the Management Board are responsible for the academic and general administration of the Institute.',
            ],
            [
                'type' => 'heading',
                'text' => 'Teaching Staff and their Qualifications',
            ],
            [
                'type' => 'table',
                'title' => '',
                'headers' => ['Name', 'Qualification', 'Position'],
                'rows' => [
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
                ],
            ],
        ];

        DB::table('site_pages')
            ->where('slug', 'leadership-and-staff')
            ->update([
                'eyebrow' => 'Leadership and Staff',
                'subtitle' => 'Administration, governing council, and teaching staff of the Institute of Development & Technology Management.',
                'content' => 'These constitute the IDTM Governing Council. The Academic Board of the Institute has a wider representation of all its facets.',
                'blocks' => json_encode($blocks),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('site_pages')
            ->where('slug', 'leadership-and-staff')
            ->update([
                'eyebrow' => 'Leadership and Staff',
                'subtitle' => 'The people who lead, teach, and support the IDTM community.',
                'content' => 'The people who lead, teach, and support the IDTM community.',
                'blocks' => json_encode([
                    ['type' => 'intro', 'text' => 'IDTM is led by experienced administrators and supported by dedicated faculty and professional staff who ensure academic excellence, student success, and smooth campus operations.'],
                    ['type' => 'cards', 'title' => 'Explore this section', 'items' => [
                        ['title' => 'Leadership Team', 'text' => 'Meet the senior leaders guiding institutional strategy and academic programmes.'],
                        ['title' => 'Faculty', 'text' => 'Academic faculty delivering postgraduate and executive programmes.'],
                        ['title' => 'Administration', 'text' => 'Professional staff supporting admissions, finance, records, and campus services.'],
                    ]],
                ]),
                'updated_at' => now(),
            ]);
    }
};
