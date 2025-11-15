<?php

namespace App;

use Carbon\Carbon;
use App\Scopes\ContratScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contrat extends Model
{
    use SoftDeletes, LogsActivity, HasFactory;

    public const STATUS_EN_COURS = 'en cours';
    public const STATUS_TERMINE = 'terminé';

    protected $guarded = [];
    protected $attributes = [
        'statut' => self::STATUS_EN_COURS,
    ];
    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;
    protected $dates = ['au', 'du'];
    protected $casts = [
        'au' => 'datetime',
        'du' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'statut' => 'string',
    ];

    public function getCheckoutAttribute($checkout)
    {
        return json_decode($checkout);
    }

    public static function boot()
    {
        parent::boot();
        static::created(function (Contrat $contrat) {
            Metric::insere($contrat);
        });
        static::updating(function (Contrat $contrat) {
            Metric::maj($contrat, $contrat->oldAttributes);
            // Metric::updateDepuis(new Contrat($contrat->oldAttributes), 'dec');
            // $contrat->total = $contrat->total();
            // Metric::updateDepuis($contrat, 'inc');
        });
        static::deleting(function (Contrat $contrat) {
            Metric::supprime($contrat);
        });
    }

    protected static function booted()
    {
        static::addGlobalScope(new ContratScope());
    }
    // Relationships

    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function contractable()
    {
        return $this->morphTo();
    }

    public function compagnie()
    {
        return $this->belongsTo('App\Compagnie');
    }
    public function paiements()
    {
        return $this->morphMany(Paiement::class, 'payable');
    }
    public function total()
    {
        return $this->nombre_jours * $this->prix_journalier + $this->demi_journee + $this->montant_chauffeur;
    }
    public function payé()
    {
        $total_payé = 0;
        foreach ($this->paiements as $paiement) {
            $total_payé += $paiement->montant;
        }
        return $total_payé;
    }
    public function solde()
    {
        return $this->total() - $this->payé();
    }
    public function logs()
    {
        return $this->morphToMany(Activity::class, 'activity_log');
    }

    // Methods
    // protected static function numéro(){
    //     $numero_contrat = Contrat::count() + 1;
    //     if($numero_contrat < 10){
    //         $numéro = '00' . $numero_contrat;
    //     } else if( $numero_contrat >= 10 && $numero_contrat < 100 ){
    //         $numéro = '0' . $numero_contrat;
    //     } else {
    //         $numéro = $numero_contrat;
    //     }
    //     return $nouveau_numéro = 'CL' . $numéro . '/' . date('m') . '/' . date('Y');
    // }

    public static function numéro()
    {
        $numéro = '';
        $numéro = DB::transaction(function () use ($numéro) {
            $contrats_du_mois = Contrat::where('compagnie_id', Auth::user()->compagnie->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->get();
            // Si nous sommes le premier du mois ...
            if (Carbon::today()->isSameDay(new Carbon('first day of this month'))) {
                // ... & aucun contrat n'est établi
                if (sizeof($contrats_du_mois) === 0) {
                    // reinitialise la numérotation du contrat a 1
                    Auth::user()->compagnie->update([
                        'numero_contrat' => 1,
                    ]);
                }
            }
            // Récupérer le numero de contrat actuel
            $numero_contrat = Auth::user()->compagnie->numero_contrat;
            if ($numero_contrat < 10) {
                $numéro = '00' . $numero_contrat;
            } elseif ($numero_contrat >= 10 && $numero_contrat < 100) {
                $numéro = '0' . $numero_contrat;
            } else {
                $numéro = $numero_contrat;
            }
            Auth::user()->compagnie->increment('numero_contrat');

            return $numéro;
        });

        return $nouveau_numéro = $numéro . '/' . date('m') . '/' . date('Y');
    }
}
