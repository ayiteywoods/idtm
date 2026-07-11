<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Cohort;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\FacultyProfile;
use App\Models\Faq;
use App\Models\Grade;
use App\Models\LearningMaterial;
use App\Models\LibraryBook;
use App\Models\PaymentInstallment;
use App\Models\PaymentPlan;
use App\Models\Programme;
use App\Models\SitePage;
use App\Models\SiteSetting;
use App\Models\Specialization;
use App\Models\StudentProfile;
use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::insert([
            ['key' => 'site_name', 'value' => 'Institute of Development & Technology Management', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tagline', 'value' => 'Knowledge and Excellence', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_title', 'value' => 'Shape Your Future in Development & Technology', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_subtitle', 'value' => 'Join a community of leaders, innovators, and change-makers at the Institute of Development & Technology Management.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_email', 'value' => 'info@idtm.edu.gh', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_phone', 'value' => '+233 208 824 029; +233 555 371 028', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_address', 'value' => 'Office of the Registrar, P. O. Box DL 494, Adisadel, Cape Coast', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'years_of_excellence', 'value' => '15+', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'footer_intro', 'value' => 'Educating Ghana\'s next generation of development and technology leaders through rigorous postgraduate programmes in Cape Coast.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        SitePage::insert([
            ['slug' => 'about', 'title' => 'About Us', 'content' => "The Institute of Development & Technology Management (IDTM) is a premier institution in Accra, Ghana.\n\nWe deliver rigorous postgraduate and executive programmes for professionals who want to lead in development policy, technology management, and innovation across Ghana and West Africa.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'mission-vision', 'title' => 'Mission & Vision', 'content' => "Our Mission\nTo develop ethical, innovative leaders in development and technology management through world-class education rooted in African realities.\n\nOur Vision\nTo be West Africa's most trusted institution for development studies, technology management, and executive education.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'leadership', 'title' => 'Leadership Team', 'content' => "The Institute of Development & Technology Management is led by experienced academics and administrators committed to academic excellence, student success, and community engagement across Ghana.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'the-rector', 'title' => 'The Rector', 'content' => "Professor John Andoh Micah serves as Rector of the Institute of Development & Technology Management, providing strategic leadership for academic excellence and institutional growth.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'rectors-welcome', 'title' => "Rector's Welcome", 'content' => "Welcome to the Institute of Development & Technology Management. We educate Ghana's next generation of development and technology leaders.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'rector-office', 'title' => 'Office Administration', 'content' => 'The Office of the Rector coordinates institutional strategy, executive communications, and liaison with governing bodies and stakeholders.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'faculty', 'title' => 'Faculty', 'content' => 'IDTM faculty combine scholarly expertise with professional practice across development policy, technology management, and business leadership.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'administration', 'title' => 'Administration', 'content' => 'Professional staff supporting admissions, student services, finance, and campus operations at IDTM.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'office-of-the-rector', 'title' => 'Office of the Rector', 'content' => '', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'leadership-and-staff', 'title' => 'Leadership and Staff', 'content' => 'The people who lead, teach, and support the IDTM community.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'campus-life', 'title' => 'Campus Life', 'content' => "Student life at IDTM includes seminars, networking events, library access, alumni mentorship, and a collaborative learning environment in the heart of Accra.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'admissions', 'title' => 'Admissions', 'content' => 'Learn about our admission requirements, application process, and programme offerings for prospective students in Ghana and internationally.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'admission-form', 'title' => 'Admission Form', 'content' => 'Apply to the Institute of Development & Technology Management by completing the admission form and submitting required documents.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'admission-requirements', 'title' => 'Entry Requirement', 'content' => "Applicants typically need a recognised bachelor's degree, relevant work experience, and supporting documents including transcripts, CV, and identification.\n\nSpecific requirements may vary by programme. Contact admissions for guidance.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'brochure', 'title' => 'Brochure', 'content' => 'Download the IDTM programme brochure for an overview of programmes, admission requirements, and campus life.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'how-to-apply', 'title' => 'How to Apply', 'content' => "1. Choose your programme and specialization.\n2. Prepare required documents.\n3. Complete the online application form.\n4. Pay the application fee.\n5. Await admission decision from the admissions committee.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'fees-funding', 'title' => 'Fees & Funding', 'content' => "Programme fees are listed on each programme page. Payment plans and installment options may be available for enrolled students through the student portal wallet.", 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'programmes', 'title' => 'Programmes', 'content' => 'Explore our MBA, executive education, and professional development programmes designed for business leaders in Ghana and across West Africa.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'alumni', 'title' => 'Alumni', 'content' => 'Stay connected with the IDTM alumni network. Access events, mentorship opportunities, and career resources.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'careers', 'title' => 'Careers', 'content' => 'Discover career opportunities at the Institute of Development & Technology Management.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'library', 'title' => 'Library', 'content' => 'Access our physical and digital library resources, research databases, and academic support services.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'academic-calendar', 'title' => 'Academic Calendar', 'content' => 'Key academic dates, teaching periods, and institutional events for MA, MPhil, and PhD programmes at IDTM.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'courses-ma-mphil-phd', 'title' => 'Courses For MA, MPhil And PhD Programmes', 'content' => 'Course offerings across MA, MPhil, and PhD Development Studies programmes at the Institute of Development & Technology Management.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'ma-mphil-admissions', 'title' => 'MA & MPhil Admissions Details', 'content' => 'Admission requirements and application guidance for MA and MPhil Development Studies programmes.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'ma-development-studies', 'title' => 'MA Development Studies', 'content' => 'The Master of Arts (Development Studies) programme is a professional development programme designed for practitioners and leaders in Ghana and West Africa.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'mphil-development-studies', 'title' => 'MPhil Development Studies', 'content' => 'The Master of Philosophy (Development Studies) programme is a research-focused pathway for advanced scholars and policy professionals.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'phd-development-studies', 'title' => 'PhD Development Studies', 'content' => 'The PhD (Development Studies) programme prepares researchers for leadership in academia, policy, and technology management.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'contact', 'title' => 'Contact Us', 'content' => 'Reach the IDTM admissions and support teams via info@idtm.edu.gh or visit our Accra campus.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'terms', 'title' => 'Terms of Service', 'content' => 'Terms of service for using the university portal and website.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'privacy', 'title' => 'Privacy Policy', 'content' => 'How we collect, use, and protect your personal information.', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $admin = User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'email' => 'admin@idtm.edu.gh',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
        ]);

        $programme = Programme::create([
            'name' => 'Dual MBA',
            'slug' => 'dual-mba',
            'description' => 'Dual specialization MBA programme',
            'total_fees' => 37800.00,
        ]);

        $cohort = Cohort::create(['name' => 'April-2026', 'start_date' => '2026-04-01']);

        $spec1 = Specialization::create([
            'programme_id' => $programme->id,
            'name' => 'MBA in Business Intelligence and Analytics',
            'order' => 1,
        ]);

        $spec2 = Specialization::create([
            'programme_id' => $programme->id,
            'name' => 'MBA in Entrepreneurship & Innovation',
            'order' => 2,
        ]);

        $course1 = Course::create([
            'programme_id' => $programme->id,
            'specialization_id' => null,
            'code' => 'MBA 503',
            'title' => 'Marketing Management',
            'is_core' => true,
        ]);

        $course2 = Course::create([
            'programme_id' => $programme->id,
            'specialization_id' => null,
            'code' => 'MBA 506',
            'title' => 'Organizational Behaviour and Human Resource Management',
            'is_core' => true,
        ]);

        $facultyUser = User::create([
            'name' => 'Dr. Kwame Mensah',
            'username' => 'kmensah',
            'email' => 'kmensah@idtm.edu.gh',
            'password' => Hash::make('password'),
            'role' => UserRole::Faculty,
        ]);

        $faculty = FacultyProfile::create([
            'user_id' => $facultyUser->id,
            'employee_id' => 'FAC001',
            'title' => 'Senior Lecturer',
            'department' => 'Business Administration',
        ]);

        $faculty->courses()->attach($course1->id, ['assigned_by' => $admin->id]);
        $faculty->courses()->attach($course2->id, ['assigned_by' => $admin->id]);

        $studentUser = User::create([
            'name' => 'Daniel Ankrah',
            'username' => 'dankrah',
            'email' => 'niiaankrah@live.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Student,
        ]);

        $student = StudentProfile::create([
            'user_id' => $studentUser->id,
            'programme_id' => $programme->id,
            'cohort_id' => $cohort->id,
            'first_specialization_id' => $spec1->id,
            'second_specialization_id' => $spec2->id,
            'index_number' => 'MB0426008E',
            'first_name' => 'Daniel',
            'other_names' => 'Nii Ardey',
            'last_name' => 'Ankrah',
            'gender' => 'Male',
            'date_of_birth' => '1984-08-09',
            'phone' => '+233208422137',
            'country' => 'GH',
            'region' => 'Greater Accra',
            'religion' => 'Christian',
            'profile_photo' => 'profile-photos/daniel-ankrah.jpg',
        ]);

        $reg1 = CourseRegistration::create([
            'student_profile_id' => $student->id,
            'course_id' => $course1->id,
            'specialization_id' => $spec1->id,
            'status' => 'registered',
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        CourseRegistration::create([
            'student_profile_id' => $student->id,
            'course_id' => $course2->id,
            'specialization_id' => $spec1->id,
            'status' => 'registered',
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        LearningMaterial::create([
            'course_id' => $course1->id,
            'faculty_profile_id' => $faculty->id,
            'title' => 'Learning Materials',
            'type' => 'material',
            'url' => 'https://example.com/mba503-materials',
        ]);

        LearningMaterial::create([
            'course_id' => $course1->id,
            'faculty_profile_id' => $faculty->id,
            'title' => 'End of Module Exam',
            'type' => 'exam',
            'url' => 'https://example.com/mba503-exam',
        ]);

        Grade::create([
            'student_profile_id' => $student->id,
            'course_id' => $course1->id,
            'faculty_profile_id' => $faculty->id,
            'type' => 'assignment',
            'title' => 'Assignment 1',
            'score' => 85,
            'max_score' => 100,
        ]);

        $plan = PaymentPlan::create([
            'student_profile_id' => $student->id,
            'total_fees' => 37800.00,
            'total_deposited' => 9450.00,
            'currency' => 'GHS',
        ]);

        PaymentInstallment::create([
            'payment_plan_id' => $plan->id,
            'period_label' => 'Month 1 Monthly Payment',
            'amount' => 3150.00,
            'due_date' => '2026-05-01',
            'status' => 'passed',
            'paid_at' => now(),
        ]);

        PaymentInstallment::create([
            'payment_plan_id' => $plan->id,
            'period_label' => 'Month 2 Monthly Payment',
            'amount' => 3150.00,
            'due_date' => '2026-09-25',
            'status' => 'upcoming',
        ]);

        LibraryBook::create([
            'uploaded_by' => $facultyUser->id,
            'title' => 'Introduction to Business Analytics',
            'author' => 'Various Authors',
            'description' => 'Comprehensive guide to business intelligence and data-driven decision making.',
            'is_published' => true,
        ]);

        Faq::insert([
            ['category' => 'Academic & Coursework', 'question' => 'How do I view my academic results and grades?', 'answer' => 'Your grades will appear in the student portal once faculty have recorded them for your registered courses.', 'sort_order' => 1, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'Academic & Coursework', 'question' => 'How do I access learning materials?', 'answer' => 'Go to Learning Materials in the student portal. Materials are available for paid course registrations.', 'sort_order' => 2, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'Fees & Payments', 'question' => 'How does the monthly payment plan work?', 'answer' => 'Your payment plan is shown in My Wallet with due dates and payment status for each installment.', 'sort_order' => 3, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        BlogPost::insert([
            [
                'title' => 'April 2026 Intake Now Open at IDTM',
                'slug' => 'april-2026-intake-open',
                'excerpt' => 'Applications are now open for the April 2026 MBA cohort. Join professionals from across Ghana advancing their careers.',
                'content' => "The Institute of Development & Technology Management is pleased to announce that applications for the April 2026 MBA intake are now open.\n\nProspective students can choose from specializations in Business Intelligence & Analytics or Entrepreneurship & Innovation. Our programmes are designed for working professionals who want to grow their leadership skills without leaving their careers.\n\nVisit our admissions page to review entry requirements and submit your application. Our team is available to guide you through every step of the process.",
                'cover_image' => '/images/hero/slide-1.jpg?v=2',
                'author' => 'IDTM Admissions',
                'category' => 'Admissions',
                'is_published' => true,
                'published_at' => now()->subDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Celebrating Our Latest Graduating Class',
                'slug' => 'celebrating-latest-graduates',
                'excerpt' => 'IDTM graduates join a growing alumni network of leaders making impact across Ghana and West Africa.',
                'content' => "The Institute of Development & Technology Management recently celebrated another cohort of MBA graduates at a ceremony held in Accra.\n\nGraduates from both specializations shared stories of career growth, new ventures, and leadership opportunities unlocked through their studies at IDTM.\n\nWe congratulate every graduate and welcome them into the IDTM alumni community. Stay connected through alumni events, mentorship programmes, and networking opportunities throughout the year.",
                'cover_image' => '/images/hero/slide-2.jpg?v=2',
                'author' => 'IDTM Communications',
                'category' => 'Campus News',
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Why Ghanaian Professionals Are Choosing an MBA in 2026',
                'slug' => 'why-ghanaian-professionals-choose-mba',
                'excerpt' => 'From entrepreneurship to corporate leadership, an MBA remains one of the most valuable investments for Ghana\'s business professionals.',
                'content' => "Ghana's economy continues to evolve, creating new opportunities for skilled leaders in development and technology. An MBA from IDTM equips professionals with analytical tools, strategic thinking, and networks needed to succeed.\n\nWhether you aim to advance in your organisation, launch a startup, or transition into a new industry, our flexible programme structure supports your goals.\n\nLearn more about our Dual MBA programme and speak with admissions to find the right specialization for your career path.",
                'cover_image' => '/images/hero/slide-3.jpg?v=2',
                'author' => 'Dr. Ama Osei-Bonsu',
                'category' => 'Insights',
                'is_published' => true,
                'published_at' => now()->subDays(18),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
