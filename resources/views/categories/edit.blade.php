<x-app-layout>
    <x-slot name="header">Modifier la categorie</x-slot>

    <div class="space-y-8">
        <div class="mx-auto w-full max-w-2xl">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50/40 px-8 py-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">
                            <i class="fas fa-pen text-amber-700"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Modifier la categorie</p>
                            <p class="text-xs text-slate-500">Mettez a jour les informations de la categorie</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="gap-6 space-y-6 p-8">
                    @csrf @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">
                            Nom de la categorie
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-tag"></i>
                            </span>
                            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                                   class="app-input pl-10 @error('name') border-rose-300 ring-2 ring-rose-200 @enderror">
                        </div>
                        @error('name')<p class="mt-2 flex items-center gap-1 text-xs font-medium text-rose-600"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-900 mb-2">Description</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-pen"></i>
                            </span>
                            <input type="text" id="description" name="description" value="{{ old('description', $category->description) }}"
                                   class="app-input pl-10">
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                            <a href="{{ route('admin.categories.index') }}" class="app-btn app-btn-secondary">
                                <i class="fas fa-times"></i>
                                Annuler
                            </a>
                            <button type="submit" class="app-btn app-btn-warning">
                                <i class="fas fa-check"></i>
                                Mettre a jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
