<?php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
    public function services()
    {
        $this->view('pages/services');
    }

    public function contact()
    {
        $this->view('pages/contact');
    }
}
