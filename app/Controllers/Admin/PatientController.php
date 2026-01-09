<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Admin\AdminAuth;
use App\Models\User;

class PatientController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    public function index()
    {
        AdminAuth::check();

        $page   = (int)($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');
        $limit  = 10;

        // Build base URL for pagination (include search if present)
        $baseUrl = '/admin/patients';
        if ($search !== '') {
            $baseUrl .= '?search=' . urlencode($search);
        }

        $totalItems = $this->userModel->countPatients($search);
        $pagination = new \App\Helpers\Pagination($totalItems, $limit, $page, $baseUrl);

        $patients = $this->userModel->getPatientsPaginated($limit, $pagination->getOffset(), $search);

        $this->view('admin/patients/index', [
            'patients'   => $patients,
            'pagination' => $pagination->getLinks(),
            'search'     => $search
        ]);
    }
}