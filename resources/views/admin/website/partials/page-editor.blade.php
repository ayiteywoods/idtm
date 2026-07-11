@php
    $blocks = old('blocks_json')
        ? json_decode(old('blocks_json'), true)
        : ($editor['blocks'] ?? []);
    $editor = [
        'eyebrow' => old('eyebrow', $editor['eyebrow'] ?? ''),
        'subtitle' => old('subtitle', $editor['subtitle'] ?? ''),
        'blocks' => is_array($blocks) ? $blocks : [],
    ];
    $blockTypes = \App\Support\SitePageContent::blockTypes();
    $blockDefaults = collect($blockTypes)
        ->mapWithKeys(fn ($label, $type) => [$type => \App\Support\SitePageContent::defaultBlock($type)])
        ->all();
@endphp

<form
    method="POST"
    action="{{ $action }}"
    enctype="multipart/form-data"
    data-page-editor-form
    class="space-y-6"
>
    @csrf
    @if (($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
        <div class="space-y-6">
            <x-portal.card>
                <label for="title" class="block text-sm font-semibold text-slate-500">Page title</label>
                <input
                    id="title"
                    name="title"
                    type="text"
                    value="{{ old('title', $page->title ?? '') }}"
                    class="mt-2 w-full border-0 bg-transparent p-0 text-3xl font-bold text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-0"
                    placeholder="Add page title"
                    required
                >
                @error('title')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </x-portal.card>

            <x-portal.card title="Page Content" description="Build the page with blocks — add text, images, lists, profiles, and more.">
                <div
                    data-page-builder
                    data-blocks='@json($editor['blocks'])'
                    class="space-y-4"
                >
                    <div class="flex flex-wrap items-center gap-3 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4">
                        <select data-page-builder-add-select class="min-w-[220px] rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                            @foreach ($blockTypes as $type => $label)
                                <option value="{{ $type }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="button" data-page-builder-add class="rounded-lg bg-brand-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-800">
                            Add block
                        </button>
                    </div>

                    <div data-page-builder-list class="space-y-4">
                        @if (empty($editor['blocks']))
                            <div class="rounded-lg border border-dashed border-slate-200 px-6 py-10 text-center text-sm text-slate-500">
                                No content blocks yet. Choose a block type above and click <strong>Add block</strong>.
                            </div>
                        @endif
                    </div>
                </div>

                <input type="hidden" name="blocks_json" id="blocks_json" value="{{ old('blocks_json', json_encode($editor['blocks'])) }}">
                @error('blocks_json')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </x-portal.card>
        </div>

        <div class="space-y-6">
            <x-portal.card title="Publish">
                <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                    <input type="hidden" name="is_published" value="0">
                    <input
                        type="checkbox"
                        name="is_published"
                        value="1"
                        class="mt-1 rounded border-slate-300 text-gold-600 accent-gold-500"
                        @checked(old('is_published', $page->is_published ?? true))
                    >
                    <span>
                        <span class="block text-sm font-semibold text-slate-700">Published</span>
                        <span class="block text-sm text-slate-500">Visible on the public website.</span>
                    </span>
                </label>

                <div class="mt-4 flex flex-wrap gap-3">
                    @if (! empty($cancelUrl))
                        <x-portal.button variant="ghost" href="{{ $cancelUrl }}" class="flex-1 justify-center">Cancel</x-portal.button>
                    @endif
                    <x-portal.button type="submit" class="flex-1 justify-center">{{ $submitLabel ?? 'Save Page' }}</x-portal.button>
                </div>
            </x-portal.card>

            <x-portal.card title="Page Settings">
                <div class="space-y-4">
                    <div>
                        <label for="slug" class="block text-sm font-semibold text-slate-700">URL slug</label>
                        <input
                            id="slug"
                            name="slug"
                            type="text"
                            value="{{ old('slug', $page->slug ?? '') }}"
                            class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm"
                            @if (! empty($page->slug)) required @endif
                            placeholder="auto-generated-from-title"
                        >
                        @error('slug')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="eyebrow" class="block text-sm font-semibold text-slate-700">Eyebrow label</label>
                        <input id="eyebrow" name="eyebrow" type="text" value="{{ old('eyebrow', $editor['eyebrow']) }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                        @error('eyebrow')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subtitle" class="block text-sm font-semibold text-slate-700">Page subtitle</label>
                        <textarea id="subtitle" name="subtitle" rows="3" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">{{ old('subtitle', $editor['subtitle']) }}</textarea>
                        @error('subtitle')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-semibold text-slate-700">Meta description</label>
                        <input id="meta_description" name="meta_description" type="text" value="{{ old('meta_description', $page->meta_description ?? '') }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
                        @error('meta_description')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-portal.card>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        window.pageBuilderDefaults = @json($blockDefaults);
    </script>
@endpush
