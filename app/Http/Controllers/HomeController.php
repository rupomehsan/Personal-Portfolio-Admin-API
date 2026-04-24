<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function aboutUs()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.service');
    }

    public function projects()
    {
        return view('pages.projects');
    }  
    public function projectEcommerce()
    {
        return view('pages.ecommerce');
    }

    public function contactUs()
    {
        return view('pages.contact');
    }

    public function blog()
    {
        return view('pages.blog');
    }

    public function termsAndCondition()
    {
        return view('pages.terms');
    }   

    public function privacyPolicy()
    {
        return view('pages.privacy');
    }


}
