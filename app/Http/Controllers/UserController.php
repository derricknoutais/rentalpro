<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function storeSignature(Request $request, User $user)
    {
        $image_64 = $request->signature; //your base64 encoded data

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1]; // .jpg .png .pdf

        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = 'signature/users/' . $user->name . '-' . $user->id;

        // return Storage::disk('public')->put($imageName, base64_decode($image));
        Storage::disk('do_spaces')->put('' . $imageName, base64_decode($image), 'public');
        if ($user->settings == null) {
            $user->settings = json_encode(['signature' => $imageName]);
        } else {
            $settings = json_decode($user->settings);
            $settings->signature = $imageName; // Changed to use object notation
            $user->settings = json_encode($settings); // Directly encode the object
        }
        $user->updated_at = now(); // Update the timestamp
        $user->save();
        return response()->json([
            'message' => 'Signature Enregistrée avec Succès.',
            'status' => 'success',
            'redirect' => '/parametres/mon-compte',
        ]);
    }
    public function destroySignature(Request $request, User $user)
    {
        $settings = json_decode($user->settings);
        if ($settings->signature) {
            Storage::disk('do_spaces')->delete($settings->signature);
            $settings->signature = null;
            $user->settings = json_encode($settings);
            $user->save();
        }
        return redirect()
            ->back()
            ->with([
                'message' => 'Signature Supprimée avec Succès.',
                'status' => 'success',
            ]);
    }
}
