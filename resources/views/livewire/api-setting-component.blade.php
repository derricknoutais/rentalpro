<div class="col-4">
    <div>
        <label for="">Identifiant Tenant</label>
        <input type="text" wire:model="tenant_id" class="form-control">
    </div>
    <div>
        <label for="">Identifiant Journal</label>
        <input type="text" wire:model="book_id" class="form-control">
    </div>
    <div>
        <label for="">Identifiant Exercice</label>
        <input type="text" wire:model="exercise_id" class="form-control">
    </div>
    <div>
        <label for="">Identifiant Compte Debit</label>
        <input type="text" wire:model="debit_account_id" class="form-control">
    </div>
    <div>
        <label for="">Libellé Debit</label>
        <input type="text" wire:model="debit_label" class="form-control">
    </div>

    <div>
        <label for="">Identifiant Compte Crédit</label>
        <input type="text" wire:model="credit_account_id" class="form-control">
    </div>
    <div>
        <label for="">Libellé Crédit</label>
        <input type="text" wire:model="credit_label" class="form-control">
    </div>
    <div class="my-5 text-center">
        <button wire:click="saveApiSettings" class="px-5 py-2 text-center bg-green-300">Enregistrer</button>
    </div>
</div>
