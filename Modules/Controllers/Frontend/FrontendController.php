<?php

namespace Modules\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FrontendController extends Controller
{

    public function HomePage()
    {
        return Inertia::render('HomePage/Index', [
            'event' => [
                'title' => 'Login Page',
            ]
        ]);
    }
    public function VocabularyPage()
    {
        return Inertia::render('Words/Index', [
            'event' => [
                'title' => 'Words Page',
            ]
        ]);
    }
    public function VocabularyTestPage()
    {
        return Inertia::render('VocabularyTest/Index', [
            'event' => [
                'title' => 'Words Page',
            ]
        ]);
    }
    public function StoryPage()
    {
        return Inertia::render('Story/Index', [
            'event' => [
                'title' => 'Story Page',
            ]
        ]);
    }
    public function StoryTestPage()
    {
        return Inertia::render('Story/StoryTest', [
            'event' => [
                'title' => 'Story Test Page',
            ]
        ]);
    }
    public function ProfilePage()
    {
        return Inertia::render('Profile/Index', [
            'event' => [
                'title' => 'Profile Page',
            ]
        ]);
    }
}
