<x-app-layout>
    <x-slot name="header">Ajouter un auteur</x-slot>

    <div class="space-y-8">

        <div class="mx-auto w-full max-w-2xl">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50/40 px-8 py-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">
                            <i class="fas fa-user-plus text-emerald-700"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Informations de l'auteur</p>
                            <p class="text-xs text-slate-500">Completez tous les champs marques d'un asterisque</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.authors.store') }}" class="gap-6 space-y-6 p-8">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">
                            Nom complet
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-pen-fancy"></i>
                            </span>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Victor Hugo"
                                   class="app-input pl-10 @error('name') border-rose-300 ring-2 ring-rose-200 @enderror">
                        </div>
                        @error('name')<p class="mt-2 flex items-center gap-1 text-xs font-medium text-rose-600"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="nationality" class="block text-sm font-semibold text-slate-900 mb-2">
                            Nationalite
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-globe"></i>
                            </span>
                            <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}" placeholder="Ex: Francaise"
                                   class="app-input pl-10">
                        </div>
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-semibold text-slate-900 mb-2">
                            Biographie
                        </label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Decrivez brievement l'auteur, ses oeuvres principales et son contexte historique..."
                                  class="app-input px-4 py-3 resize-none">{{ old('bio') }}</textarea>
                        <p class="mt-1.5 text-xs text-slate-400">Maximum 1000 caracteres</p>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                            <a href="{{ route('admin.authors.index') }}"
                               class="app-btn app-btn-secondary">
                                <i class="fas fa-times"></i>
                                Annuler
                            </a>
                            <button type="submit"
                                    class="app-btn app-btn-success">
                                <i class="fas fa-check"></i>
                                Enregistrer l'auteur
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
