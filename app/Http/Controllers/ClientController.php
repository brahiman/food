<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function showClientLoginForm()
    {
        return view('client.login');
    }

    public function showClientRegisterForm()
    {
        return view('client.register');
    }

    public function clientRegisterSubmit(Request $request)
    {
        // 1. Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20', // Ajout de la validation pour le téléphone
            'address' => 'nullable|string|max:255' // Ajout de la validation pour l'adresse
        ],
            [
                'email.unique' => 'L\'e-mail a déjà été pris.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            ]
        );

        // 2. Création du client avec Eloquent
        try {
            $client = Client::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'password' => Hash::make($validated['password']),
                'role' => 'client',
                'status' => '0'
            ]);
        } catch (\Exception $e) {
            // Gestion des erreurs
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement']);
        }
        $notification = array(
            'message' => 'Client enregistré avec succès',
            'alert-type' => 'success'
        );
        // 3. Redirection avec message de succès
        return redirect()->route('client.login')->with($notification);
    }

//end Method

    public function clientLoginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password']
        ];
        if (Auth::guard('client')->attempt($data)) {
            return redirect()->route('client.dashboard')->with('success', 'Connexion réussie');
        } else {
            return redirect()->route('client.login')->with('error', 'Identifiants incorrects');
        }
    }

    //end Method

    public function clientDashboard()
    {
        return view('client.index');
    }

    public function clientLogout()
    {
        Auth::guard('client')->logout();
        return redirect()->route('client.login')->with('success', 'Deconnexion réussie');
    }

    //end Method

    public function clientProfileView()
    {
        $id = Auth::guard('client')->id();
        $profilData = Client::find($id);
        return view('client.profile', compact('profilData'));
    }

    //end Method

    public function clientProfileUpdate(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . Auth::guard('client')->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048', // Limite à 2MB, doit être une image
        ]);

        try {
            // Récupérer le client connecté
            $client = Auth::guard('client')->user();
            if (!$client) {
                return redirect()->back()->with('error', 'Utilisateur non authentifié.');
            }

            // Mettre à jour les champs
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->address = $request->address;

            // Gestion de la photo
            if ($request->hasFile('photo')) {
                $oldPhotoPath = $client->photo;

                // Stocker la nouvelle photo avec Laravel Storage
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('upload/client_img/'), $filename);
                $client->photo = $filename;

                // Supprimer l'ancienne photo si elle existe
                if ($oldPhotoPath && $oldPhotoPath !== $filename) {
                    $this->deleteOldPhoto($oldPhotoPath);
                }
            }
            // Sauvegarder les modifications
            $client->save();
            // Notification de succès
            $notification = [
                'message' => 'Profil mis à jour avec succès.',
                'alert-type' => 'success'
            ];
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            // Notification d'erreur
            $notification = [
                'message' => 'Une erreur est survenue : ' . $e->getMessage(),
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }

    //end Method

    public function clientChangePasswordForm()
    {
        $profilData = Auth::guard('client')->user();

        if (!$profilData) {
            return redirect()->route('client.login')->with('error', 'Veuillez vous connecter.');
        }

        return view('client.change-password', compact('profilData'));
    }

//end Method
    public function clientChangePasswordSubmit(Request $request)
    {
        $request->validate([
            'oldpwd' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ], [
                'oldpwd.required' => 'Le mot de passe actuel est requis.',
                'new_password.required' => 'Le nouveau mot de passe est requis.',
                'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
                'new_password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
            ]
        );
        $profilData = Auth::guard('client')->user();

        if (!$profilData) {
            $notification = [
                'message' => 'Utilisateur non authentifié.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->oldpwd, $profilData->password)) {
            $notification = [
                'message' => 'L’ancien mot de passe est incorrect.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }

        // Mettre à jour le mot de passe
        $profilData->password = Hash::make($request->new_password);
        $profilData->save();

        $notification = [
            'message' => 'Mot de passe mis à jour avec succès.',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }
    //end Method
}
