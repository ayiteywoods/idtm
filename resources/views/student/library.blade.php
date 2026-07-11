@extends('layouts.portal')

@section('title', 'Online Library')
@section('breadcrumb', 'Student Portal / Resources')

@section('content')
<x-portal.page-header title="Online Resources" description="Access academic databases and research materials." />

<x-portal.card title="Academic Databases" class="mb-6">
    <p class="mb-4 text-sm text-slate-500">Explore comprehensive research databases and academic journals.</p>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
            ['name' => 'EBSCOhost', 'desc' => 'Academic journals, books, and articles across various fields.'],
            ['name' => 'JSTOR', 'desc' => 'Scholarly articles, research papers, and books.'],
            ['name' => 'Sage Publications', 'desc' => 'Research tools and academic articles.'],
            ['name' => 'Emerald', 'desc' => 'Business, management, and social sciences journals.'],
            ['name' => 'African Journals Online', 'desc' => 'African research journals across disciplines.'],
        ] as $db)
            <div class="rounded-lg border border-slate-200 bg-slate-50/50 p-4 transition hover:border-brand-200 hover:bg-brand-50/30">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-100 text-brand-600">
                    <x-portal.icon name="library" class="h-5 w-5" />
                </div>
                <h3 class="mt-3 font-semibold text-slate-900">{{ $db['name'] }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $db['desc'] }}</p>
            </div>
        @endforeach
    </div>
</x-portal.card>

<h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Digital Library</h2>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($books as $book)
        <x-portal.card>
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <x-portal.icon name="book" />
            </div>
            <h3 class="mt-3 font-semibold text-slate-900">{{ $book->title }}</h3>
            @if ($book->author)<p class="text-sm text-slate-500">{{ $book->author }}</p>@endif
            @if ($book->description)<p class="mt-2 text-sm text-slate-600">{{ Str::limit($book->description, 100) }}</p>@endif
        </x-portal.card>
    @empty
        <div class="col-span-full"><x-portal.empty-state title="No library resources yet" description="Books uploaded by faculty will appear here." icon="library" /></div>
    @endforelse
</div>
@if ($books->hasPages())<div class="mt-6">{{ $books->links() }}</div>@endif
@endsection
