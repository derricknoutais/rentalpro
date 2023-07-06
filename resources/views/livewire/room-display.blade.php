<div class="flex w-full">
    <div class="w-1/3 grid grid-cols-3 flex justify-between mt-3">
        @foreach ($contractables as $contractable)
            <div

                @if ($contractable->etat === 'disponible')
                    class="border border-black p-5 bg-green-200 mb-3 mr-3 cursor-pointer"
                @elseif($contractable->etat === 'loué')
                    class="border border-black p-5 bg-red-200 mb-3 mr-3 cursor-pointer"
                @elseif($contractable->etat === 'maintenance')   
                    class="border border-black p-5 bg-black mb-3 mr-3 cursor-pointer text-white"
                @endif
                wire:click="afficheContractable({{ $contractable->id }})"
            >
                {{ $contractable->nom() }}
            </div>
        @endforeach
    </div>

    <div class="
            @if ( $display && $display->etat === 'disponible' )
                bg-green-100
            @elseif( $display && $display->etat === 'loué' )
                bg-red-100
            @elseif( $display && $display->etat === 'maintenance' )
                bg-black-100
            @endif
            w-2/3 border border-black px-5
        "
    >
        @if ($display)
            <h1 class="text-4xl  text-center font-bold mt-12">{{ $display->nom() }}</h1>
            <p class="text-2xl mt-12">Type : {{ $display->type }}</p>
            <p class="text-2xl">Etat Actuel : {{ $display->etat }}</p>
            @if ($display->etat === 'disponible')
                <a class="btn btn-primary mt-5" href="/contrats/create?contractable_id={{$display->id}}">Créer un contrat</a>
            @elseif($display->etat === 'loué')
                <div>
                    Dernier Contrat 
                </div>
            @endif
            
        @else
        <div class="w-full flex flex-col justify-center items-center ">
            <img src="img/orishainn_logo.png" alt="" class="w-1/2">
            <p class="text-center text-4xl mt-5">
                Cliquez sur une Chambre pour afficher ses details
            </p>

        </div>


        @endif
    </div>


</div>
