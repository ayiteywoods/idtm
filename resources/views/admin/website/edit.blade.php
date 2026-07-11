@extends('layouts.portal')

@section('title', 'Edit Page')
@section('breadcrumb', 'Administration / Website / Edit Page')

@section('content')
<x-portal.page-header title="Edit {{ $page->title }}" description="Customize page content, images, and layout blocks.">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.website.index') }}">Back to CMS</x-portal.button>
        <x-portal.button variant="secondary" href="{{ route('pages.show', $page->slug) }}" target="_blank">
            <x-portal.icon name="external" class="h-4 w-4" /> View Page
        </x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

@include('admin.website.partials.page-editor', [
    'action' => route('admin.website.pages.update', $page),
    'method' => 'PUT',
    'page' => $page,
    'editor' => $editor,
    'cancelUrl' => route('admin.website.index'),
    'submitLabel' => 'Save Changes',
])
@endsection
