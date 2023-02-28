<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FAQController extends MainController
{
    public function getIndex()

    {

        return View::make('faq')

            ->withFaq(Faq::all())

            ->withTitle(trans('main.FAQ'));

    }
}
