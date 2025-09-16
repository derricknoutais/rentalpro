<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function associerDocument(Request $request)
    {
        $document = Document::find($request->document_id);
        $document->voiture_id = $request->voiture_id;
        $document->date_expiration = $request->date_expiration;
        $document->save();

        return response()->json($document);
    }

    public function destroy(Document $document)
    {
        $document->delete();
    }
}
