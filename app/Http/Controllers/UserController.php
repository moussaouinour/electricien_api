<?php

namespace App\Http\Controllers;

// use App\Models\User ;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{





    public function update(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . auth()->user()->id,
            'adresse' => 'nullable|string|max:255',
            'anniversaire' => 'nullable|date_format:Y-m-d',
        ]);

        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        // Récupérer l'utilisateur authentifié
        $user = auth()->user();

        // Mettre à jour les informations
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('adresse')) {
            $user->adresse = $request->adresse;
        }

        if ($request->has('anniversaire')) {
            $user->anniversaire = $request->anniversaire;
        }

        // Sauvegarder les changements
     //   $user->save();

        // Retourner une réponse de succès
        return response()->json([
            'message' => 'Informations mises à jour avec succès.',
            'user' => $user
        ], 200);
    }

    /**
     * Mettre à jour le mot de passe de l'utilisateur
     */

    public function updatePassword(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // Confirmation du mot de passe
            'new_password_confirmation' => 'required|string|min:8', // Validation de la confirmation
        ]);

        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Vérification du mot de passe actuel
        $user = Auth::user(); // Obtenir l'utilisateur authentifié
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Le mot de passe actuel est incorrect.'], 400);
        }

        // Mise à jour du mot de passe
        $user->password = Hash::make($request->new_password);
       //  $user->save();

        return response()->json(['message' => 'Mot de passe mis à jour avec succès.'], 200);
    }
}
