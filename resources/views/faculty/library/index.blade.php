@extends('layouts.portal')

@section('title', 'Online Library')
@section('breadcrumb', 'Faculty Portal / Resources')

@section('content')
<x-portal.page-header title="Online Library" description="Upload books and research resources for students." />

<x-portal.card title="Upload Book" class="mb-6">
    <form method="POST" action="{{ route('faculty.library.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
        @csrf
        <div>
            <label for="title" class="block text-sm font-semibold text-slate-700">Title</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
        </div>
        <div>
            <label for="author" class="block text-sm font-semibold text-slate-700">Author</label>
            <input id="author" name="author" type="text" value="{{ old('author') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        </div>
        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
            <textarea id="description" name="description" rows="3" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">{{ old('description') }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label for="file" class="block text-sm font-semibold text-slate-700">File</label>
            <input id="file" name="file" type="file" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gold-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-900 hover:file:bg-gold-200" required>
            <p class="mt-1 text-xs text-slate-500">PDF, DOC, PPT, XLS, ZIP, or EPUB · up to 50 MB.</p>
            @error('file')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div class="md:col-span-2 flex justify-end">
            <x-portal.button type="submit"><x-portal.icon name="plus" class="h-4 w-4" /> Upload Book</x-portal.button>
        </div>
    </form>
</x-portal.card>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($books as $book)
        <x-portal.card>
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-blue-600 ring-1 ring-blue-100">
                <x-portal.icon name="book" class="h-6 w-6" />
            </div>
            <h3 class="mt-4 font-semibold text-slate-900">{{ $book->title }}</h3>
            @if ($book->author)<p class="text-sm text-slate-500">{{ $book->author }}</p>@endif
            @if ($book->file_path)
                <a href="{{ route('faculty.library.download', $book) }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700"><x-portal.icon name="folder" class="h-4 w-4" /> Download</a>
            @elseif ($book->external_url)
                <a href="{{ $book->external_url }}" target="_blank" class="mt-3 inline-block text-sm font-medium text-brand-600 hover:text-brand-700">Open resource</a>
            @endif
            <div class="mt-4 flex items-center justify-between">
                <x-portal.badge :variant="$book->is_published ? 'success' : 'muted'">{{ $book->is_published ? 'Published' : 'Draft' }}</x-portal.badge>
                <form method="POST" action="{{ route('faculty.library.destroy', $book) }}" onsubmit="return confirm('Delete this book?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-700">Delete</button>
                </form>
            </div>
        </x-portal.card>
    @empty
        <div class="col-span-full">
            <x-portal.empty-state title="No books uploaded" description="Upload books and research materials to the online library." icon="library" />
        </div>
    @endforelse
</div>
@if ($books->hasPages())<div class="mt-6">{{ $books->links() }}</div>@endif
@endsection
