<div class="container flex flex-col justify-between">
    <div class="flex justify-between">
        <div class="flex flex-col items-center w-1/2">
            <h1 class="text-2xl">Pannes</h1>
            <div class="flex items-center">
                <div class="mt-6">
                    <label for="">Description</label>
                    <textarea class="form-control" wire:model="description_panne"></textarea>
                </div>
                <div class="ml-6">
                    <button class="mt-6 bg-blue-500 btn" wire:click="ajouterPanne">Ajouter Panne</button>
                </div>
            </div>
            <ul role="list" class="divide-y divide-gray-200">
                @foreach ($pannes as $panne)
                    <li class="flex py-4">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $panne['description'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="flex flex-col justify-center w-1/2">
            <div>
                <label for="">Date</label>
                <input type="date" class="form-control" wire:model="created_at">
            </div>
            <div>
                <label for="">Titre</label>
                <input type="text" class="form-control" wire:model="titre">
            </div>
            <div>
                <label for="">Voiture</label>
                <select wire:model="voiture_id" class="form-control">
                    @foreach ($voitures as $voiture)
                    <option value="{{ $voiture->id }}">{{ $voiture->immatriculation }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="">Technicien</label>
                <select wire:model="technicien_id" class="form-control">
                    @foreach ($techniciens as $technicien)
                    <option value="{{ $technicien->id }}">{{ $technicien->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="">Main d'Oeuvre</label>
                <input wire:model="coût" type="text" class="form-control">
            </div>
            <div>
                <label for="">Coût Pièces</label>
                <input wire:model="coût_pièces" type="text" class="form-control">
            </div>
        </div>

    </div>

    <button class="mt-12 bg-blue-500 btn" wire:click="creerMaintenance">Créer Maintenance</button>
</div>

