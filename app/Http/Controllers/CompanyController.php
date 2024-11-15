<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class CompanyController extends Controller
{
    
    public function create(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_size' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'intervention_zone' => 'required|string|max:255',
            'website' => 'required|url',
            'email' => 'required|email',
            'description' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validation du logo
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Sauvegarder l'entreprise
        $company = new Company();
        $company->company_name = $request->company_name;
        $company->company_size = $request->company_size;
        $company->address = $request->address;
        $company->intervention_zone = $request->intervention_zone;
        $company->website = $request->website;
        $company->email = $request->email;
        $company->description = $request->description;

        // Sauvegarder le logo si fourni
        if ($request->hasFile('logo')) {
            $company->logo = $request->file('logo')->store('logos', 'public');
        }

        $company->save();

        return response()->json($company, 201);
    }


    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_size' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'intervention_zone' => 'required|string|max:255',
            'website' => 'required|url',
            'email' => 'required|email',
            'description' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $company = Company::findOrFail($id);

        // Mettre à jour les informations de l'entreprise
        $company->company_name = $request->company_name;
        $company->company_size = $request->company_size;
        $company->address = $request->address;
        $company->intervention_zone = $request->intervention_zone;
        $company->website = $request->website;
        $company->email = $request->email;
        $company->description = $request->description;

        // Mettre à jour le logo si un nouveau logo est téléchargé
        if ($request->hasFile('logo')) {

            if ($company->logo && Storage::exists('public/' . $company->logo)) {
                Storage::delete('public/' . $company->logo);
            }
            $company->logo = $request->file('logo')->store('logos', 'public');
        }

        $company->save();

        return response()->json($company, 200);
    }


public function showCompanyInfo(Request $request)
{

    $user = $request->user();


    $company = $user->company;


    if (!$company) {
        return response()->json(['message' => 'Aucune entreprise associée à cet utilisateur'], 404);
    }


    return response()->json($company, 200);
}

}
