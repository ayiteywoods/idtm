@extends('layouts.portal')

@section('title', 'Learning Materials')
@section('breadcrumb', 'Faculty Portal / Teaching')

@section('content')
<x-portal.page-header title="Learning Materials" description="Upload and manage course resources for your students." />

@if ($courses->isNotEmpty())
    <x-portal.card title="Upload Material" class="mb-6">
        <form method="POST" action="{{ route('faculty.materials.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
            @csrf
            <div>
                <label for="course_id" class="block text-sm font-semibold text-slate-700">Course</label>
                <select id="course_id" name="course_id" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected((int) old('course_id') === $course->id)>{{ $course->code }} — {{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="type" class="block text-sm font-semibold text-slate-700">Type</label>
                <select id="type" name="type" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
                    <option value="material" @selected(old('type') === 'material')>Learning Material</option>
                    <option value="exam" @selected(old('type') === 'exam')>Exam</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-semibold text-slate-700">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm" required>
            </div>
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-semibold text-slate-700">Description <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea id="description" name="description" rows="2" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">{{ old('description') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label for="file" class="block text-sm font-semibold text-slate-700">File</label>
                <input id="file" name="file" type="file" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gold-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-900 hover:file:bg-gold-200" required>
                <p class="mt-1 text-xs text-slate-500">PDF, DOC, PPT, XLS, or ZIP · up to 20 MB.</p>
                @error('file')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2 flex justify-end">
                <x-portal.button type="submit"><x-portal.icon name="plus" class="h-4 w-4" /> Upload Material</x-portal.button>
            </div>
        </form>
    </x-portal.card>
@endif

<x-portal.card :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3.5">Title</th>
                    <th class="px-6 py-3.5">Course</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Uploaded</th>
                    <th class="px-6 py-3.5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($materials as $material)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $material->title }}</td>
                        <td class="px-6 py-4"><x-portal.badge variant="muted">{{ $material->course->code }}</x-portal.badge></td>
                        <td class="px-6 py-4"><x-portal.badge :variant="$material->type === 'exam' ? 'warning' : 'info'">{{ ucfirst($material->type) }}</x-portal.badge></td>
                        <td class="px-6 py-4 text-slate-500">{{ $material->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if ($material->file_path)
                                    <a href="{{ route('faculty.materials.download', $material) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Download</a>
                                @elseif ($material->url)
                                    <a href="{{ $material->url }}" target="_blank" class="text-sm font-medium text-brand-600 hover:text-brand-700">Open</a>
                                @endif
                                <form method="POST" action="{{ route('faculty.materials.destroy', $material) }}" onsubmit="return confirm('Delete this material?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12"><x-portal.empty-state title="No materials uploaded" description="Upload learning materials and exams for your courses." icon="folder" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($materials->hasPages())<div class="border-t border-slate-100 px-6 py-4">{{ $materials->links() }}</div>@endif
</x-portal.card>
@endsection
