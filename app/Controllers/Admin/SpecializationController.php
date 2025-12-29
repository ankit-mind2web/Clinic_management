<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Admin\AdminAuth;
use App\Models\Admin\SpecializationModel;

class SpecializationController extends Controller
{
    public function index()
    {
        AdminAuth::check();

        $model = new SpecializationModel();

        $perPage = 5;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $specializations = $model->getPaginated($perPage, $offset);
        $total = $model->countAll();

        $pagination = [
            'current_page' => $page,
            'total_pages'  => ceil($total / $perPage),
            'base_url'     => '/admin/specializations'
        ];

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            require __DIR__ . '/../../Views/admin/specializations/table.php';
            exit;
        }

        // Normal page load
        $this->view('admin/specializations/index', compact('specializations', 'pagination'));
    }


    public function create()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('admin/specializations/create');
            return;
        }

        header('Content-Type: application/json');

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Specialization name is required'
            ]);
            exit;
        }

        try {
            (new SpecializationModel())->create($name, $description);

            echo json_encode([
                'status' => 'success',
                'message' => 'Specialization added successfully'
            ]);
        } catch (\Throwable $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Specialization already exists'
            ]);
        }
        exit;
    }

    public function edit()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $id = (int)($_GET['id'] ?? 0);
            $model = new SpecializationModel();
            $specialization = $model->getById($id);

            if (!$specialization) {
                die('Specialization not found');
            }

            $this->view('admin/specializations/edit', [
                'specialization' => $specialization
            ]);
            return;
        }

        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($id <= 0 || $name === '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid data'
            ]);
            exit;
        }

        (new SpecializationModel())->update($id, $name, $description);

        echo json_encode([
            'status' => 'success',
            'message' => 'Specialization updated successfully'
        ]);
        exit;
    }

    public function delete()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new SpecializationModel())->delete((int)$_POST['id']);
        }

        header('Location: /admin/specializations');
        exit;
    }
}
