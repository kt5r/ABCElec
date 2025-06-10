<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage(Request $request, string $locale)
    {
        if (!in_array($locale, ['en', 'si'])) {
            abort(404);
        }

        // Store in session
        Session::put('locale', $locale);
        
        // Don't translate the flash message - just use English for now to test
        Session::flash('success', 'Language switched to ' . $locale);
        
        return redirect()->back();
    }
}
