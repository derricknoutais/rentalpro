<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function destroy(Document $document)
    {
        $document->delete();
    }
}
