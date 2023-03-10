<div class="flex w-full">
    <div class="w-1/3 grid grid-cols-3 flex justify-between mt-3">
        @foreach ($contractables as $contractable)
            <div

                @if ($contractable->etat === 'Disponible')
                    class="border border-black p-5 bg-green-200 mb-3 mr-3 cursor-pointer"
                @else
                    class="border border-black p-5 bg-red-200 mb-3 mr-3 cursor-pointer"
                @endif
                wire:click="afficheContractable({{ $contractable->id }})"
            >
                {{ $contractable->nom() }}
            </div>
        @endforeach
    </div>

    <div class="
            @if ( $display && $display->etat === 'Disponible' )
                bg-green-100
            @elseif( $display && $display->etat === 'Loué' )
                bg-red-100
            @endif
            w-2/3 border border-black px-5
        "
    >
        @if ($display)
            <h1 class="text-4xl  text-center font-bold mt-12">{{ $display->nom() }}</h1>
            <p class="text-2xl mt-12">Type : {{ $display->type }}</p>
            <p class="text-2xl">Etat Actuel : {{ $display->etat }}</p>
            <p class="text-2xl mt-5">Offres :</p>
            @if ($display->type === 'Budget')
                <p class="text-2xl"> Detente : 10.000 F CFA ( 04H00 ) </p>
                <p class="text-2xl"> Nuitee : 15.000 F CFA ( 22h00 - 08h00 ) </p>
                <p class="text-2xl"> H24 : 20.000 F CFA ( 13h00 - 12h59 ) </p>
                <p class="text-2xl"> W48 : 35.000 F CFA ( 04H00 ) </p>
                <p class="text-2xl"> W72 : 50.000 F CFA ( 04H00 ) </p>
                <p class="text-2xl"> 7J : 110.000 F CFA ( 04H00 ) </p>
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
