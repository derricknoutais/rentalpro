<div>
    <form action="/paiement" method="POST" class="flex flex-col">
        @csrf
        <input type="hidden" class="border border-gray-300 rounded-md " name="contrat_id" value="{{ $contrat->id }}">
        @if ($contrat->caution)
            <div class="flex items-center justify-start ">
                <input type="checkbox" class="mr-4" name="payer_avec_caution" wire:model="payer_avec_caution">
                <label for="" class="m-0">Payer avec Caution
                    @if ( $payer_avec_caution && isset($montant) )
                        <span>
                            ( {{ number_format( ($contrat->caution -   (float) $montant ) , 0, ',' , '.')}} FCFA )
                        </span>
                    @endif
                </label>
            </div>
        @endif
        <div class="flex flex-col items-start">
            <label for="">Montant</label>
            <input type="number" class="w-full p-2 border border-gray-300 rounded-md" name="montant" wire:model="montant">
        </div>
        <div class="flex flex-col items-start">
            <label for="">Note</label>
            <textarea type="number" class="w-full px-2 py-6 border border-gray-300 rounded-md" name="note"></textarea>
        </div>
        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
            <button type="submit"
                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                Payer
            </button>
            <button type="button" @click="closeModal()"
                class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                Cancel
            </button>
        </div>
    </form>
</div>
