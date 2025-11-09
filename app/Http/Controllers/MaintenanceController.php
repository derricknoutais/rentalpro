<?php

namespace App\Http\Controllers;

use App\Panne;
use App\Voiture;
use App\ApiSetting;
use App\Chambre;
use App\Technicien;
use App\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Charge la collection des maintenances de la compagnie de l'utilisateur connecte
        $maintenances = Auth::user()->compagnie->maintenances;

        // Charge les relations associes aux maintenances
        if (sizeof($maintenances)) {
            $maintenances->loadMissing(['contractable', 'technicien', 'pannes']);
        }

        // $maintenances = Maintenance::with(['voiture', 'technicien','pannes'])->latest()->get();
        return view('maintenances.index', compact('maintenances'));
    }
    public function getApi()
    {
        $maintenances = Auth::user()->compagnie->maintenances;

        // Charge les relations associes aux maintenances
        if (sizeof($maintenances)) {
            $maintenances->loadMissing(['contractable', 'technicien', 'pannes']);
        }
        return response()->json($maintenances);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $voitures = Voiture::all();
        $contractables = Auth::user()->contractables;
        $techniciens = Technicien::all();
        return view('maintenances.create', compact('voitures', 'techniciens', 'contractables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validateCreationRequest($request);
        $this->createMaintenanceFromPayload($validated);

        return redirect()->back()->with('status', 'Maintenance créée');
    }
    public function storeApi(Request $request)
    {
        $validated = $this->validateCreationRequest($request);
        $maintenance = $this->createMaintenanceFromPayload($validated);

        return response()->json($maintenance, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function show(Maintenance $maintenance)
    {
        $maintenance->loadMissing(['contractable', 'technicien', 'pannes']);

        $contractableUrl = $maintenance->contractable ? url('/contractables/' . $maintenance->contractable->id) : null;
        $technicienUrl = $maintenance->technicien ? url('/techniciens/' . $maintenance->technicien->id) : null;
        $printUrl = url('/maintenance/' . $maintenance->id . '/print');

        return view('maintenances.show', compact('maintenance', 'contractableUrl', 'technicienUrl', 'printUrl'));
    }

    public function print(Maintenance $maintenance)
    {
        // $maintenance->numero_recu = 116031;
        // $sale = Http::withToken(env('VEND_TOKEN'))->get('https://stapog.vendhq.com/api/2.0/search?type=sales&invoice_number=' . $maintenance->numero_recu);
        // $prods = [];
        // foreach ($sale['data'][0]['line_items'] as $line) {
        //     $prods[] = Http::withToken(env('VEND_TOKEN'))->get('https://stapog.vendhq.com/api/2.0/products/' . $line['product_id'])['data']['variant_name'];
        // }

        // return view('maintenances.print', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function edit(Maintenance $maintenance)
    {
        $maintenance->loadMissing('pannes');
        $voitures = Voiture::all();
        $techniciens = Technicien::all();
        return view('maintenances.edit', compact('voitures', 'techniciens', 'maintenance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $this->ensureMaintenanceBelongsToCompany($maintenance);

        $data = $this->validateUpdatePayload($request);
        $maintenance = $this->applyMaintenanceUpdate($maintenance, $data);

        if ($request->wantsJson()) {
            return response()->json($maintenance);
        }

        return redirect()->back()->with('status', 'Maintenance mise à jour');
    }

    public function updateApi(Request $request, Maintenance $maintenance)
    {
        $this->ensureMaintenanceBelongsToCompany($maintenance);
        $data = $this->validateUpdatePayload($request);

        $maintenance = $this->applyMaintenanceUpdate($maintenance, $data);

        return response()->json($maintenance);
    }

    public function destroy(Maintenance $maintenance)
    {
        $this->ensureMaintenanceBelongsToCompany($maintenance);
        $this->deleteMaintenance($maintenance);
        return redirect()->back()->with('status', 'Maintenance supprimée');
    }

    public function destroyApi(Maintenance $maintenance)
    {
        $this->ensureMaintenanceBelongsToCompany($maintenance);
        $this->deleteMaintenance($maintenance);

        return response()->json([
            'message' => 'Maintenance supprimée',
        ]);
    }

    public function envoyerMaintenanceGescash(Maintenance $maintenance)
    {
        $done = DB::transaction(function () use ($maintenance) {
            $apiSettings = ApiSetting::where('compagnie_id', Auth::user()->compagnie->id)->first();
            $transactionData = [
                'transaction_date' => $maintenance->created_at,
                'tenant_id' => $apiSettings->gescash_tenant_id,
                'book_id' => $apiSettings->gescash_book_id,
                'exercise_id' => $apiSettings->gescash_exercise_id,
                'attachment' => 'https://rentalpro.azimuts.ga/maintenance/' . $maintenance->id,
                'entries' => [
                    // Client Entry Debit
                    [
                        'account_id' => $apiSettings->gescash_maintenance_account_id,
                        'label' => 'Maintenance sur ' . $maintenance->voiture->immatriculation . ' pour ' . $maintenance->titre . ' par ' . $maintenance->technicien->nom,
                        'debit' => $maintenance->coût + $maintenance->coût_pièces,
                        'credit' => null,
                        'created_at' => $maintenance->created_at,
                        'updated_at' => now(),
                    ],
                    // Service Entry Credit
                    [
                        'account_id' => $apiSettings->gescash_cash_account_id,
                        'label' => 'Maintenance sur ' . $maintenance->voiture->immatriculation . ' pour ' . $maintenance->titre . ' par ' . $maintenance->technicien->nom,
                        'credit' => $maintenance->coût + $maintenance->coût_pièces,
                        'debit' => null,
                        'created_at' => $maintenance->created_at,
                        'updated_at' => now(),
                    ],
                ],
            ];
            $sent = Http::post(env('GESCASH_BASE_URL') . '/api/v1/transaction', $transactionData);
            if ($sent->status() == 201) {
                $maintenance->update([
                    'gescash_transaction_id' => $sent->json()['id'],
                ]);
                return true;
            }
        });
        if ($done) {
            return redirect()->back();
        }
    }

    protected function validateCreationRequest(Request $request): array
    {
        return $request->validate([
            'contractable_id' => 'required|integer',
            'technicien_id' => 'required|exists:techniciens,id',
            'titre' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'panne_ids' => 'required|array|min:1',
            'panne_ids.*' => 'integer|distinct',
        ]);
    }

    protected function createMaintenanceFromPayload(array $data)
    {
        $compagnie = Auth::user()->compagnie;
        $contractable = $compagnie->contractables()->findOrFail($data['contractable_id']);

        $panneIds = collect($data['panne_ids'])->unique()->values()->all();

        $pannes = Panne::query()->whereIn('id', $panneIds)->where('contractable_id', $contractable->id)->whereNull('maintenance_id')->where('etat', 'non-résolue')->get();

        if ($pannes->count() !== count($panneIds)) {
            abort(422, 'Certaines pannes ne sont plus disponibles pour cette maintenance.');
        }

        return DB::transaction(function () use ($compagnie, $contractable, $data, $pannes) {
            $maintenance = Maintenance::create([
                'compagnie_id' => $compagnie->id,
                'contractable_id' => $contractable->id,
                'contractable_type' => get_class($contractable),
                'voiture_id' => $compagnie->isVehicules() ? $contractable->id : null,
                'technicien_id' => $data['technicien_id'],
                'titre' => $data['titre'] ?? null,
                'note' => $data['note'] ?? null,
                'statut' => 'en cours',
            ]);

            foreach ($pannes as $panne) {
                $panne->update([
                    'maintenance_id' => $maintenance->id,
                    'etat' => 'en-maintenance',
                ]);
            }

            if ($compagnie->isVehicules() && method_exists($contractable, 'etat')) {
                $contractable->etat('maintenance');
            }

            return $maintenance->loadMissing(['contractable', 'technicien', 'pannes']);
        });
    }

    protected function validateUpdatePayload(Request $request): array
    {
        return $request->validate([
            'titre' => 'nullable|string|max:255',
            'technicien_id' => 'nullable|exists:techniciens,id',
            'coût' => 'nullable|numeric|min:0',
            'coût_pièces' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'statut' => 'nullable|in:en cours,en pause',
        ]);
    }

    protected function applyMaintenanceUpdate(Maintenance $maintenance, array $data)
    {
        $payload = [];

        foreach (['titre', 'technicien_id', 'note', 'statut'] as $field) {
            if (array_key_exists($field, $data)) {
                $payload[$field] = $data[$field];
            }
        }

        foreach (['coût', 'coût_pièces'] as $costField) {
            if (array_key_exists($costField, $data)) {
                $payload[$costField] = $data[$costField];
            }
        }

        if (count($payload)) {
            $maintenance->update($payload);
        }

        return $maintenance->fresh()->loadMissing(['contractable', 'technicien', 'pannes']);
    }

    protected function deleteMaintenance(Maintenance $maintenance): void
    {
        DB::transaction(function () use ($maintenance) {
            $maintenance->pannes()->update([
                'etat' => 'non-résolue',
                'maintenance_id' => null,
            ]);

            $maintenance->delete();
        });
    }

    protected function ensureMaintenanceBelongsToCompany(Maintenance $maintenance): void
    {
        abort_if($maintenance->compagnie_id !== Auth::user()->compagnie_id, 403);
    }

    public function complete(Request $request, Maintenance $maintenance)
    {
        $this->ensureMaintenanceBelongsToCompany($maintenance);

        abort_if($maintenance->statut === 'terminé', 422, 'Cette maintenance est déjà terminée.');

        $data = $request->validate([
            'pannes_resolues' => 'required|array|min:1',
            'pannes_resolues.*' => 'integer|distinct',
        ]);

        $panneIds = collect($data['pannes_resolues'])->unique()->values();

        $maintenance = DB::transaction(function () use ($maintenance, $panneIds) {
            $pannesAResoudre = $maintenance->pannes()->whereIn('id', $panneIds)->get();

            if ($pannesAResoudre->count() !== $panneIds->count()) {
                abort(422, 'Certaines pannes sélectionnées ne sont pas associées à cette maintenance.');
            }

            foreach ($pannesAResoudre as $panne) {
                $panne->update([
                    'etat' => 'résolue',
                ]);
            }

            $maintenance
                ->pannes()
                ->whereNotIn('id', $panneIds)
                ->update([
                    'etat' => 'non-résolue',
                    'maintenance_id' => null,
                ]);

            $maintenance->update([
                'statut' => 'terminé',
            ]);

            if ($maintenance->contractable && method_exists($maintenance->contractable, 'etat')) {
                $maintenance->contractable->etat('disponible');
            }

            return $maintenance->fresh()->loadMissing(['contractable', 'technicien', 'pannes']);
        });

        if ($request->wantsJson()) {
            return response()->json($maintenance);
        }

        return redirect()->back()->with('status', 'Maintenance terminée');
    }
}
