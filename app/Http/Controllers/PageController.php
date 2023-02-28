<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{

	
    public function showPage($alias)

    {

        $alias =  $alias;

        $alias = str_replace("/", '', stripslashes($alias));

        $page = Page::where('alias', $alias)->first();

        if ($page)

            return View::make('page')

                ->with('page' , $page)

                ->with('title', $page->{'title'.LANG});

        else

            return Redirect::to('404');

    }

}
