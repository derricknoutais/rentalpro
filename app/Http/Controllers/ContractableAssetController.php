<?php

namespace App\Http\Controllers;

use App\Accessoire;
use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractableAssetController extends Controller
{
    protected function findContractable($contractableId)
    {
        $compagnie = Auth::user()->compagnie;
        return $compagnie->contractables()->findOrFail($contractableId);
    }

    protected function ensureSupportsRelation($contractable, $relation)
    {
        abort_unless(
            method_exists($contractable, $relation),
            422,
            "Ce contractable ne possède pas la relation {$relation}."
        );
    }

    protected function responseDocument($contractable, $documentId)
    {
        return $contractable->documents()->where('documents.id', $documentId)->first();
    }

    protected function responseAccessoire($contractable, $accessoireId)
    {
        return $contractable->accessoires()->where('accessoires.id', $accessoireId)->first();
    }

    public function storeDocument(Request $request, $contractableId)
    {
        $contractable = $this->findContractable($contractableId);
        $this->ensureSupportsRelation($contractable, 'documents');

        $data = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'date_expiration' => 'nullable|date',
        ]);

        $document = Auth::user()->compagnie->documents()->findOrFail($data['document_id']);

        $contractable->documents()->syncWithoutDetaching([
            $document->id => ['date_expiration' => $data['date_expiration'] ?: null],
        ]);

        return response()->json($this->responseDocument($contractable, $document->id));
    }

    public function updateDocument(Request $request, $contractableId, $documentId)
    {
        $contractable = $this->findContractable($contractableId);
        $this->ensureSupportsRelation($contractable, 'documents');

        $data = $request->validate([
            'document_id' => 'sometimes|exists:documents,id',
            'date_expiration' => 'nullable|date',
        ]);

        $targetDocumentId = $data['document_id'] ?? $documentId;

        $document = Auth::user()->compagnie->documents()->findOrFail($targetDocumentId);

        $contractable->documents()->syncWithoutDetaching([
            $document->id => ['date_expiration' => $data['date_expiration'] ?: null],
        ]);

        return response()->json($this->responseDocument($contractable, $document->id));
    }

    public function destroyDocument($contractableId, $documentId)
    {
        $contractable = $this->findContractable($contractableId);
        $this->ensureSupportsRelation($contractable, 'documents');

        $contractable->documents()->detach($documentId);

        return response()->json(['status' => 'deleted']);
    }

    public function storeAccessoire(Request $request, $contractableId)
    {
        $contractable = $this->findContractable($contractableId);
        $this->ensureSupportsRelation($contractable, 'accessoires');

        $data = $request->validate([
            'accessoire_id' => 'required|exists:accessoires,id',
            'quantite' => 'required|numeric|min:1',
        ]);

        $accessoire = Auth::user()->compagnie->accessoires()->findOrFail($data['accessoire_id']);

        $contractable->accessoires()->syncWithoutDetaching([
            $accessoire->id => ['quantité' => $data['quantite']],
        ]);

        return response()->json($this->responseAccessoire($contractable, $accessoire->id));
    }

    public function updateAccessoire(Request $request, $contractableId, $accessoireId)
    {
        $contractable = $this->findContractable($contractableId);
        $this->ensureSupportsRelation($contractable, 'accessoires');

        $data = $request->validate([
            'accessoire_id' => 'sometimes|exists:accessoires,id',
            'quantite' => 'required|numeric|min:1',
        ]);

        $targetAccessoireId = $data['accessoire_id'] ?? $accessoireId;

        $accessoire = Auth::user()->compagnie->accessoires()->findOrFail($targetAccessoireId);

        $contractable->accessoires()->syncWithoutDetaching([
            $accessoire->id => ['quantité' => $data['quantite']],
        ]);

        return response()->json($this->responseAccessoire($contractable, $accessoire->id));
    }

    public function destroyAccessoire($contractableId, $accessoireId)
    {
        $contractable = $this->findContractable($contractableId);
        $this->ensureSupportsRelation($contractable, 'accessoires');

        $contractable->accessoires()->detach($accessoireId);

        return response()->json(['status' => 'deleted']);
    }
}
