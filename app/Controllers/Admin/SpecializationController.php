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

        $this->view('admin/specializations/index', [
            'specializations' => $model->getAll()
        ]);
    }

    public function create()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if ($name !== '') {
                (new SpecializationModel())->create($name);
            }
            header('Location: /admin/specializations');
            exit;
        }

        $this->view('admin/specializations/create');
    }

    public function edit()
    {
        AdminAuth::check();

        $id = (int)($_GET['id'] ?? 0);
        $model = new SpecializationModel();
        $specialization = $model->getById($id);

        if (!$specialization) {
            die('Specialization not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if ($name !== '') {
                $model->update($id, $name);
            }
            header('Location: /admin/specializations');
            exit;
        }

        $this->view('admin/specializations/edit', [
            'specialization' => $specialization
        ]);
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
