@extends('layouts.portal')

@section('title', 'New Page')
@section('breadcrumb', 'Administration / Website / New Page')

@section('content')
<x-portal.page-header title="New Page" description="Create a new public page with customizable content blocks and images.">
    <x-slot:actions>
        <x-portal.button variant="ghost" href="{{ route('admin.website.index') }}">Back to CMS</x-portal.button>
    </x-slot:actions>
</x-portal.page-header>

@include('admin.website.partials.page-editor', [
    'action' => route('admin.website.pages.store'),
    'method' => 'POST',
    'page' => new \App\Models\SitePage(),
    'editor' => [
        'eyebrow' => old('eyebrow', ''),
        'subtitle' => old('subtitle', ''),
        'blocks' => old('blocks_json') ? json_decode(old('blocks_json'), true) : [],
    ],
    'cancelUrl' => route('admin.website.index'),
    'submitLabel' => 'Create Page',
])
@endsection
