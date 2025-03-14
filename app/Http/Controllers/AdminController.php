<?php

namespace App\Http\Controllers;

use App\Mail\Websitemail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function showAdminLoginForm()
    {
        return view('admin.login');
    }

    //end Method

    public function adminDashboard()
    {
        return view('admin.index');
    }

    //end Method

    public function adminLoginSubmit(Request $request)
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
        if (Auth::guard('admin')->attempt($data)) {
            return redirect()->route('admin.dashboard')->with('success', 'Connexion réussie');
        } else {
            return redirect()->route('admin.login')->with('error', 'Identifiants incorrects');
        }
    }
    //end Method

    public function adminLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Deconnexion réussie');
    }

    //end Method
    public function showAdminForgetPasswordForm()
    {
        return view('admin.forgetpassword');
    }

    //end Method
    public function adminForgetPasswordSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $admin_data = Admin::where('email', $request->email)->first();
        if (!$admin_data) {
            return redirect()->back()->with('error', 'Email incorrect');
        }
        $token = hash("sha256", time());
        $admin_data->token = $token;
        $admin_data->update();

        $reset_link = url('admin/reset-password/' . $token . '/' . $admin_data->email);
        $subject = "Reset password";
        $message = "<p>Dear Admin, Pleace click on belon link to reset your password</p>";
        $message .= "<p><a href='" . $reset_link . "'>" . $reset_link . "</a></p>";
        $message .= "<p>Regards, Team Team</p>";

        \Mail::to($request->email)->send(new Websitemail($subject, $message));
        return redirect()->back()->with('success', 'Reset password link sent on your email');

    }

    //end Method
    public function showAdminResetPasswordForm($token, $email)
    {
        $admin_data = Admin::where('token', $token)->where('email', $email)->first();
        if (!$admin_data) {
            return redirect()->back()->with('error', 'Token or Email incorrect');
        }
        return view('admin.resetpassword', compact('token', 'email'));
    }

    //end Method
    public function adminResetPasswordSubmit(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);
        $admin_data = Admin::where('token', $request->token)->where('email', $request->email)->first();
        $admin_data->password = Hash::make($request->password);
        $admin_data->token = "";
        $admin_data->update();

        return redirect()->route('admin.login')->with('success', 'Password reset successfully');
    }

    //end Method
    public function adminProfileView()
    {
        $id = Auth::guard('admin')->id();
        $profilData = Admin::find($id);
        return view('admin.profile', compact('profilData'));
    }

    //end Method

    public function adminProfileStore(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . Auth::guard('admin')->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048', // Limite à 2MB, doit être une image
        ]);

        try {
            // Récupérer l'admin connecté
            $admin = Auth::guard('admin')->user();
            if (!$admin) {
                return redirect()->back()->with('error', 'Utilisateur non authentifié.');
            }

            // Mettre à jour les champs
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->address = $request->address;

            // Gestion de la photo
            if ($request->hasFile('photo')) {
                $oldPhotoPath = $admin->photo;

                // Stocker la nouvelle photo avec Laravel Storage
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('upload/admin_img/'), $filename);
                $admin->photo = $filename;

                // Supprimer l'ancienne photo si elle existe
                if ($oldPhotoPath && $oldPhotoPath !== $filename) {
                    $this->deleteOldPhoto($oldPhotoPath);
                }
            }
            // Sauvegarder les modifications
            $admin->save();
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
    private function deleteOldPhoto(string $oldPhotoPath): void
    {
        // Utiliser Storage pour supprimer l'ancienne photo
        $fullPath = public_path('upload/admin_img/' . $oldPhotoPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
//end private Method*/

    // Afficher le formulaire de changement de mot de passe
    public function adminChangePwdForm()
    {
        $profilData = Auth::guard('admin')->user();

        if (!$profilData) {
            return redirect()->route('admin.login')->with('error', 'Veuillez vous connecter.');
        }

        return view('admin.changepwd', compact('profilData'));
    }

    public function adminChangePwdSubmit(Request $request)
    {
        // Validation des données
        $request->validate([
            'oldpwd' => 'required',
            'new_password' => 'required|string|min:8|confirmed', // confirmed exige new_password_confirmation
        ],
            [
                'oldpwd.required' => 'Le mot de passe actuel est requis.',
                'new_password.required' => 'Le nouveau mot de passe est requis.',
                'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
                'new_password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
            ]);

        $profilData = Auth::guard('admin')->user();

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


}
