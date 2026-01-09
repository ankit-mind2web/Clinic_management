<?php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
    public function services()
    {
        $specModel = new \App\Models\Doctor\DoctorSpecialization();
        $services = $specModel->getAllSpecializations();

        // Transform if necessary or use as is in view
        // The view expects: title, description, link
        // DB has: name, description
        
        $this->view('pages/services', ['services' => $services]);
    }

    public function serviceDetail()
    {
        $slug = $_GET['service'] ?? '';
        
        $specModel = new \App\Models\Doctor\DoctorSpecialization();
        // Since slug is just the name in lowercase usually, but our DB has 'Cardiology' (Capitalized)
        // We might need to handle case sensitivity or fuzzy matching.
        // For now let's try to match exactly or case-insensitive if DB collation permits.
        // Or better: decode the slug.
        
        // Let's assume the slug passed is 'cardiology' and DB has 'Cardiology'.
        // We can try to fetch by name.
        
        // Simple trick: Try to find any specialization where LOWER(name) = lowercase slug
        // But getByName query uses =, which is case insensitive in MySQL usually.
        
        $service = $specModel->getByName($slug);

        $this->view('pages/service_detail', ['service' => $service]);
    }

    public function contact()
    {
        $this->view('pages/contact');
    }
}
