<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;

class GeneralSettingsController extends Controller
{
    public function show(GeneralSettings $settings)
    {
        return view('settings.show', [
            'site_name' => $settings->site_name,
            'admin_email' => $settings->admin_email,
        ]);
    }

    public function update(Request $request, GeneralSettings $settings)
    {
        $settings->site_name = $request->input('site_name');
        $settings->admin_email = $request->input('admin_email');
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated!');
    }
}