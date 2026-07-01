@props(['model', 'disabled' => false])

<div wire:ignore x-data="initQuill(@js($model), @js($disabled))" x-init="init()" class="w-full">
    <div x-ref="editor"
        class="min-h-[200px] bg-white dark:bg-zinc-800 border border-neutral-300 dark:border-neutral-700 p-3 dark:text-white">
    </div>
</div>


@once
    <style>
        .ql-editor ol {
            margin-left: 1.2rem;
        }

        .ql-editor ol[style*="lower-alpha"] {
            list-style-type: lower-alpha;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/quill-table-better@1/dist/quill-table-better.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-table-better@1/dist/quill-table-better.js"></script>

    <script>
        function initQuill(model, disable) {
            return {
                quill: null,
                init() {
                    const el = this.$refs.editor;
                    if (!el) return; // Prevent null error
                    if (this.quill !== null) return;
                    if (el.classList.contains('ql-container')) return;

                    // Register plugin once
                    if (!Quill.imports['modules/table-better']) {
                        Quill.register({
                            'modules/table-better': QuillTableBetter
                        }, true);
                    }

                    const toolbarOptions = [
                        [{
                            header: [1, 2, 3, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        ['blockquote', 'code-block'],
                        ['clean'],
                        ['table-better']
                    ];

                    this.quill = new Quill(el, {
                        theme: 'snow',
                        modules: {
                            toolbar: toolbarOptions,
                            table: true,
                            'table-better': {
                                language: 'en_US',
                                menus: ['column', 'row', 'merge', 'table', 'cell', 'wrap', 'copy', 'delete'],
                                toolbarTable: true
                            },
                            keyboard: {
                                bindings: QuillTableBetter.keyboardBindings
                            }
                        }
                    });
                    console.log(disable)
                    this.quill.enable(!disable);
                    // Load initial Livewire data
                    this.quill.root.innerHTML = @this.get(model) ?? '';

                    // Push to Livewire
                    this.quill.on('text-change', () => {
                        @this.set(model, this.quill.root.innerHTML);
                    });

                    // Livewire refresh
                    Livewire.on('updated-editor', ({
                        model: refreshed
                    }) => {
                        if (refreshed !== model) return;
                        this.quill.root.innerHTML = @this.get(model) ?? '';
                    });
                }
            }
        }
    </script>
@endonce
