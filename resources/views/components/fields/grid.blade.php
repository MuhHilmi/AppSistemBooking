@props(['fields'])

<div
    x-data="{
        showModal: false,
        selectedField: null,
        openModal(field) { this.selectedField = field; this.showModal = true; },
        closeModal() { this.showModal = false; }
    }"
    x-on:keydown.escape.window="closeModal()"
>
    <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($fields as $field)
            <x-fields.card :field="$field" />
        @empty
            <p class="col-span-full text-center text-slate-500">
                Belum ada lapangan yang tersedia saat ini.
            </p>
        @endforelse
    </div>

    <x-fields.modal />
</div>
