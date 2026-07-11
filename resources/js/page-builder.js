const BLOCK_LABELS = {
    intro: 'Intro paragraph',
    heading: 'Section heading',
    image: 'Image',
    'image-text': 'Image with text',
    'rector-profile': 'Rector profile',
    list: 'Bullet list',
    cards: 'Info cards',
    split: 'Two columns',
    people: 'People profiles',
    steps: 'Numbered steps',
    stats: 'Statistics',
    table: 'Data table',
    cta: 'Call to action',
};

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

function linesToItems(text) {
    return String(text ?? '')
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter(Boolean);
}

function itemsToLines(items) {
    return Array.isArray(items) ? items.join('\n') : '';
}

function defaultBlock(type) {
    const defaults = window.pageBuilderDefaults ?? {};
    return structuredClone(defaults[type] ?? { type, text: '' });
}

function setPath(object, path, value) {
    const keys = path.split('.');
    let current = object;

    for (let index = 0; index < keys.length; index++) {
        const key = keys[index];
        const isLast = index === keys.length - 1;

        if (isLast) {
            current[key] = value;
            return;
        }

        const nextKey = keys[index + 1];

        if (/^\d+$/.test(nextKey)) {
            const arrayIndex = Number(nextKey);

            if (!Array.isArray(current[key])) {
                current[key] = [];
            }

            const keyAfterNext = keys[index + 2] ?? null;

            if (keyAfterNext !== null && /^\d+$/.test(keyAfterNext)) {
                if (!Array.isArray(current[key][arrayIndex])) {
                    current[key][arrayIndex] = [];
                }

                current = current[key][arrayIndex];
                index += 1;
                continue;
            }

            if (index + 2 === keys.length - 1) {
                current[key][arrayIndex] = value;
                return;
            }

            current[key][arrayIndex] = current[key][arrayIndex] ?? {};
            current = current[key][arrayIndex];
            index += 1;
            continue;
        }

        current[key] = current[key] ?? {};
        current = current[key];
    }
}

class PageBuilder {
    constructor(root) {
        this.root = root;
        this.blocks = JSON.parse(root.dataset.blocks || '[]');
        this.list = root.querySelector('[data-page-builder-list]');
        this.jsonInput = document.getElementById('blocks_json');
        this.addSelect = root.querySelector('[data-page-builder-add-select]');
        this.addButton = root.querySelector('[data-page-builder-add]');

        root.__pageBuilder = this;

        this.addButton?.addEventListener('click', () => {
            const type = this.addSelect?.value;
            if (!type) {
                return;
            }

            this.blocks.push(defaultBlock(type));
            this.render();
        });

        this.list?.addEventListener('click', (event) => {
            const action = event.target.closest('[data-action]');
            if (!action) {
                return;
            }

            const index = Number(action.dataset.index);
            const actionName = action.dataset.action;

            if (actionName === 'move-up' && index > 0) {
                [this.blocks[index - 1], this.blocks[index]] = [this.blocks[index], this.blocks[index - 1]];
                this.render();
            }

            if (actionName === 'move-down' && index < this.blocks.length - 1) {
                [this.blocks[index + 1], this.blocks[index]] = [this.blocks[index], this.blocks[index + 1]];
                this.render();
            }

            if (actionName === 'remove') {
                this.blocks.splice(index, 1);
                this.render();
            }

            if (actionName === 'add-item') {
                this.addNestedItem(index, action.dataset.itemType);
                this.render();
            }

            if (actionName === 'remove-item') {
                this.removeNestedItem(index, action.dataset.itemType, Number(action.dataset.itemIndex));
                this.render();
            }
        });

        this.list?.addEventListener('input', () => this.syncFromDom());
        this.list?.addEventListener('change', (event) => {
            if (event.target.type === 'file') {
                this.previewImage(event.target);
            }

            this.syncFromDom();
        });

        this.render();
    }

    addNestedItem(blockIndex, itemType) {
        const block = this.blocks[blockIndex];
        if (!block) {
            return;
        }

        const templates = {
            cards: { title: '', text: '' },
            people: { name: '', role: '', bio: '', image: '' },
            steps: { title: '', text: '' },
            stats: { value: '', label: '' },
            split: { title: '', text: '' },
            tableRow: ['', ''],
        };

        if (itemType === 'tableRow') {
            block.rows = block.rows ?? [];
            block.rows.push([...(templates.tableRow)]);
            return;
        }

        block.items = block.items ?? [];
        block.items.push({ ...(templates[itemType] ?? {}) });
    }

    removeNestedItem(blockIndex, itemType, itemIndex) {
        const block = this.blocks[blockIndex];
        if (!block) {
            return;
        }

        if (itemType === 'tableRow') {
            block.rows?.splice(itemIndex, 1);
            return;
        }

        block.items?.splice(itemIndex, 1);
    }

    previewImage(input) {
        const preview = input.closest('[data-image-field]')?.querySelector('[data-image-preview]');
        if (!preview || !input.files?.[0]) {
            return;
        }

        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('hidden');
    }

    syncFromDom() {
        this.list?.querySelectorAll('[data-block-index]').forEach((card) => {
            const index = Number(card.dataset.blockIndex);
            const block = this.blocks[index];
            if (!block) {
                return;
            }

            card.querySelectorAll('[data-field]').forEach((field) => {
                if (field.type === 'file') {
                    return;
                }

                const key = field.dataset.field;
                const value = field.dataset.lines === 'true' ? linesToItems(field.value) : field.value;

                if (key.includes('.')) {
                    setPath(block, key, value);
                } else {
                    block[key] = value;
                }
            });
        });

        this.persist();
    }

    persist() {
        if (this.jsonInput) {
            this.jsonInput.value = JSON.stringify(this.blocks);
        }
    }

    render() {
        if (!this.list) {
            return;
        }

        this.list.innerHTML = this.blocks.map((block, index) => this.renderBlock(block, index)).join('');
        this.persist();
    }

    blockActions(index) {
        return `
            <div class="flex flex-wrap gap-2">
                <button type="button" data-action="move-up" data-index="${index}" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-50">Move up</button>
                <button type="button" data-action="move-down" data-index="${index}" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-50">Move down</button>
                <button type="button" data-action="remove" data-index="${index}" class="rounded-md border border-rose-200 px-2 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50">Remove</button>
            </div>
        `;
    }

    imageField(index, image = '', nestedKey = null) {
        const name = nestedKey === null ? `block_images[${index}]` : `block_images[${index}][${nestedKey}]`;

        return `
            <div data-image-field class="space-y-3">
                <img data-image-preview src="${escapeHtml(image)}" alt="" class="${image ? '' : 'hidden '}h-40 w-full rounded-lg border border-slate-200 object-cover">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Upload image</label>
                    <input type="file" name="${name}" accept="image/*" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gold-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-900">
                    <p class="mt-1 text-xs text-slate-500">Leave empty to keep the current image.</p>
                </div>
            </div>
        `;
    }

    simpleInput(label, field, value, rows = 1) {
        if (rows > 1) {
            return `
                <div>
                    <label class="block text-sm font-semibold text-slate-700">${label}</label>
                    <textarea data-field="${field}" rows="${rows}" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">${escapeHtml(value)}</textarea>
                </div>
            `;
        }

        return `
            <div>
                <label class="block text-sm font-semibold text-slate-700">${label}</label>
                <input data-field="${field}" type="text" value="${escapeHtml(value)}" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
            </div>
        `;
    }

    renderBlock(block, index) {
        const type = block.type ?? 'intro';
        let body = '';

        switch (type) {
            case 'intro':
                body = this.simpleInput('Paragraph', 'text', block.text ?? '', 5);
                break;
            case 'heading':
                body = this.simpleInput('Heading', 'text', block.text ?? '');
                break;
            case 'image':
                body = `
                    ${this.imageField(index, block.image)}
                    ${this.simpleInput('Alt text', 'alt', block.alt ?? '')}
                    ${this.simpleInput('Caption (optional)', 'caption', block.caption ?? '')}
                `;
                break;
            case 'image-text':
                body = `
                    <div class="grid gap-6 lg:grid-cols-2">
                        ${this.imageField(index, block.image)}
                        <div class="space-y-4">
                            ${this.simpleInput('Alt text', 'alt', block.alt ?? '')}
                            ${this.simpleInput('Title', 'title', block.title ?? '')}
                            ${this.simpleInput('Text', 'text', block.text ?? '', 5)}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Image position</label>
                                <select data-field="position" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                                    <option value="left" ${block.position === 'left' ? 'selected' : ''}>Image left</option>
                                    <option value="right" ${block.position === 'right' ? 'selected' : ''}>Image right</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                break;
            case 'rector-profile':
                body = `
                    <div class="grid gap-6 lg:grid-cols-2">
                        ${this.imageField(index, block.image)}
                        <div class="space-y-4">
                            ${this.simpleInput('Alt text', 'alt', block.alt ?? '')}
                            ${this.simpleInput('Name', 'name', block.name ?? '')}
                            ${this.simpleInput('Role', 'role', block.role ?? 'Rector')}
                        </div>
                    </div>
                    ${this.simpleInput('Welcome opening', 'intro', block.intro ?? '', 4)}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Welcome message</label>
                        <textarea data-field="message" data-lines="true" rows="8" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">${escapeHtml(itemsToLines(block.message))}</textarea>
                        <p class="mt-1 text-xs text-slate-500">One paragraph per line.</p>
                    </div>
                `;
                break;
            case 'list':
                body = `
                    ${this.simpleInput('Section title (optional)', 'title', block.title ?? '')}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">List items</label>
                        <textarea data-field="items" data-lines="true" rows="6" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">${escapeHtml(itemsToLines(block.items))}</textarea>
                    </div>
                `;
                break;
            case 'cards':
                body = `
                    ${this.simpleInput('Section title', 'title', block.title ?? '')}
                    <div class="space-y-4">
                        ${(block.items ?? []).map((item, itemIndex) => `
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-700">Card ${itemIndex + 1}</p>
                                    <button type="button" data-action="remove-item" data-index="${index}" data-item-type="cards" data-item-index="${itemIndex}" class="text-xs font-medium text-rose-600">Remove</button>
                                </div>
                                ${this.simpleInput('Title', `items.${itemIndex}.title`, item.title ?? '')}
                                <div class="mt-3">${this.simpleInput('Text', `items.${itemIndex}.text`, item.text ?? '', 3)}</div>
                            </div>
                        `).join('')}
                    </div>
                    <button type="button" data-action="add-item" data-index="${index}" data-item-type="cards" class="rounded-md border border-dashed border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Add card</button>
                `;
                break;
            case 'split':
                body = (block.items ?? []).map((item, itemIndex) => `
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="mb-3 text-sm font-semibold text-slate-700">Column ${itemIndex + 1}</p>
                        ${this.simpleInput('Title', `items.${itemIndex}.title`, item.title ?? '')}
                        <div class="mt-3">${this.simpleInput('Text', `items.${itemIndex}.text`, item.text ?? '', 4)}</div>
                    </div>
                `).join('');
                break;
            case 'people':
                body = `
                    <div class="space-y-4">
                        ${(block.items ?? []).map((item, itemIndex) => `
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-700">Person ${itemIndex + 1}</p>
                                    <button type="button" data-action="remove-item" data-index="${index}" data-item-type="people" data-item-index="${itemIndex}" class="text-xs font-medium text-rose-600">Remove</button>
                                </div>
                                <div class="grid gap-4 lg:grid-cols-2">
                                    ${this.imageField(index, item.image ?? '', itemIndex)}
                                    <div class="space-y-3">
                                        ${this.simpleInput('Name', `items.${itemIndex}.name`, item.name ?? '')}
                                        ${this.simpleInput('Role', `items.${itemIndex}.role`, item.role ?? '')}
                                    </div>
                                </div>
                                <div class="mt-3">${this.simpleInput('Bio', `items.${itemIndex}.bio`, item.bio ?? '', 3)}</div>
                            </div>
                        `).join('')}
                    </div>
                    <button type="button" data-action="add-item" data-index="${index}" data-item-type="people" class="rounded-md border border-dashed border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Add person</button>
                `;
                break;
            case 'steps':
                body = `
                    ${this.simpleInput('Section title', 'title', block.title ?? '')}
                    <div class="space-y-4">
                        ${(block.items ?? []).map((item, itemIndex) => `
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-700">Step ${itemIndex + 1}</p>
                                    <button type="button" data-action="remove-item" data-index="${index}" data-item-type="steps" data-item-index="${itemIndex}" class="text-xs font-medium text-rose-600">Remove</button>
                                </div>
                                ${this.simpleInput('Title', `items.${itemIndex}.title`, item.title ?? '')}
                                <div class="mt-3">${this.simpleInput('Text', `items.${itemIndex}.text`, item.text ?? '', 3)}</div>
                            </div>
                        `).join('')}
                    </div>
                    <button type="button" data-action="add-item" data-index="${index}" data-item-type="steps" class="rounded-md border border-dashed border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Add step</button>
                `;
                break;
            case 'stats':
                body = `
                    <div class="space-y-4">
                        ${(block.items ?? []).map((item, itemIndex) => `
                            <div class="grid gap-4 rounded-lg border border-slate-200 bg-slate-50 p-4 md:grid-cols-2">
                                ${this.simpleInput('Value', `items.${itemIndex}.value`, item.value ?? '')}
                                ${this.simpleInput('Label', `items.${itemIndex}.label`, item.label ?? '')}
                            </div>
                        `).join('')}
                    </div>
                    <button type="button" data-action="add-item" data-index="${index}" data-item-type="stats" class="mt-3 rounded-md border border-dashed border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Add stat</button>
                `;
                break;
            case 'table':
                body = `
                    ${this.simpleInput('Section title', 'title', block.title ?? '')}
                    <div class="grid gap-4 md:grid-cols-2">
                        ${(block.headers ?? []).map((header, headerIndex) => this.simpleInput(`Header ${headerIndex + 1}`, `headers.${headerIndex}`, header)).join('')}
                    </div>
                    <div class="space-y-3">
                        ${(block.rows ?? []).map((row, rowIndex) => `
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-700">Row ${rowIndex + 1}</p>
                                    <button type="button" data-action="remove-item" data-index="${index}" data-item-type="tableRow" data-item-index="${rowIndex}" class="text-xs font-medium text-rose-600">Remove</button>
                                </div>
                                <div class="grid gap-3 md:grid-cols-2">
                                    ${(row ?? []).map((cell, cellIndex) => this.simpleInput(`Cell ${cellIndex + 1}`, `rows.${rowIndex}.${cellIndex}`, cell)).join('')}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <button type="button" data-action="add-item" data-index="${index}" data-item-type="tableRow" class="rounded-md border border-dashed border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Add row</button>
                `;
                break;
            case 'cta':
                body = `
                    ${this.simpleInput('Title', 'title', block.title ?? '')}
                    ${this.simpleInput('Text', 'text', block.text ?? '', 3)}
                    ${this.simpleInput('Button label', 'primary.label', block.primary?.label ?? '')}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Button route</label>
                        <select data-field="primary.route" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                            <option value="contact" ${block.primary?.route === 'contact' ? 'selected' : ''}>Contact</option>
                            <option value="home" ${block.primary?.route === 'home' ? 'selected' : ''}>Home</option>
                            <option value="pages.show" ${block.primary?.route === 'pages.show' ? 'selected' : ''}>Another page</option>
                        </select>
                    </div>
                `;
                break;
            default:
                body = this.simpleInput('Content', 'text', block.text ?? '', 4);
        }

        return `
            <article data-block-index="${index}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gold-700">Content block</p>
                        <h3 class="text-base font-bold text-slate-900">${BLOCK_LABELS[type] ?? type}</h3>
                    </div>
                    ${this.blockActions(index)}
                </div>
                <div class="space-y-4">${body}</div>
            </article>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-page-builder]').forEach((root) => {
        new PageBuilder(root);
    });

    document.querySelector('[data-page-editor-form]')?.addEventListener('submit', () => {
        document.querySelectorAll('[data-page-builder]').forEach((root) => {
            root.__pageBuilder?.syncFromDom();
        });
    });
});
