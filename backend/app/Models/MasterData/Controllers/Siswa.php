<?php
namespace App\Models\MasterData\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MasterData\Models\SiswaModel;

class Siswa extends BaseController{
    use ResponseTrait;

    public function index(){
        $model = new SiswaModel();

        $data = $model->findAll();

        if ($data) {
            return $this->respond([
                'status' => 200,
                'message' => 'Data Siswa sudah di temukan',
                'data' => $data
            ]);
        }else{
            return $this->respond([
                'status' => 200,
                'message' => 'maaf data siswa tidak di temukan',
                'data' => []
            ]);
        }
    }

}

?>