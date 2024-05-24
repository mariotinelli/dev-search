<x-ts-input
    label="Nome *"
    placeholder="Nome do assistente"
    wire:model="form.name"
/>

<div class="grid grid-cols-2 gap-2" >
    <x-ts-input
        label="E-mail *"
        placeholder="E-mail do assistente"
        wire:model="form.email"
    />

    <x-ts-input
        x-mask="999.999.999-99"
        label="CPF *"
        placeholder="CPF do assistente"
        wire:model="form.cpf"
    />
</div >
