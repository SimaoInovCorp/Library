<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->is_admin) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display the settings form
     */
    public function index(): View
    {
        $settings = Setting::whereIn('key', [
            'tax_rate',
            'shipping_cost',
            'free_shipping_threshold',
            'max_cart_quantity_per_book',
            'abandoned_cart_hours',
        ])->get()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            foreach ($validated as $key => $value) {
                Setting::set($key, $value);
            }

            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update settings: ' . $e->getMessage())
                ->withInput();
        }
    }
}
