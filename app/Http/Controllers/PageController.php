<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Nette\Utils\Validators;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $data['title']      = 'PAGES';
        $data['apiUrl']     = request()->getSchemeAndHttpHost() . "/api/";
        $data['categories'] = Category::get();
        return view('pages.index', $data);
    }
}
