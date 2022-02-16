<?php

namespace App\Http\Controllers;

use FormGenerator\FormGenerator;
use Illuminate\Http\Request;
use OpticalMarkReader\OpticalMarkReaderModels\OMRExamsModel;
use OpticalMarkReader\OpticalMarkReaderModels\OMRExamStatsModel;
use OpticalMarkReader\OpticalReader\OpticalReaderManagerFormOperation;


class OpticalFormTXTUploadController extends Controller
{
    public function index()
    {
        return view('optical-form-insert/optical-form-insert',['controller'=>$this]);
    }

    public function update(Request $request)
    {
        if (isset($request['save'])) {
            if (isset($_FILES['file']['tmp_name'])) {
                $OpticalReaderManager = new OpticalReaderManagerFormOperation($_POST, $_FILES['file']['tmp_name']);
                $OpticalReaderManager->insert();

                if ($OpticalReaderManager->isResult()) {
                    $operation_result_ok = true;
                }
            } else {
                $error_message = 'Dosya yüklenemedi!';
            }
        }
    }

    public function getForm()
    {
        $form_generator_array = [

            /**
             * json,XML,HTML
             */
            'export' => [
                //'format' => 'Bootstrapv3FormWizard',
                'format' => 'Bootstrapv3Form',
                'type' => 'html'
            ],
            'inputs' => [
                'exam-insert' => [
                    [
                        'type' => 'form_section',
                        'label' => 'Sınav Bilgisi Girme İşlemi'
                    ],
                    [
                        'type' => 'select',
                        'help_block' => '<a href="javascript:;" class="open_help_video btn btn-sm btn-info"><i class="fa fa-video-camera"></i> Yardım Videosunu İzle</a>',
                        'label' => 'Sınav',
                        'add_button_at_same_place' => [
                            'set_button' => true,
                            'add-url' => '/optical-reader-exams/create',
                        ],
                        'attributes' => [
                            'name' => 'exam_id'
                        ],
                        'options' => [
                            'data' => [
                                'from' => 'sql',
                                'sql' => "SELECT * FROM "
                                    . OMRExamsModel::table()
                                    . " WHERE id NOT IN(SELECT id FROM " . OMRExamStatsModel::table() . " ) "
                                ,
                            ]
                        ],
                        'option_settings' => [
                            'key' => 'id',
                            'label' => 'name'
                        ],
                    ],
                    [
                        'type' => 'file',
                        'label' => 'Optik Okuma Formu Dosyası/Çıktısı',
                        'attributes' => [
                            'name' => 'file'
                        ]
                    ]
                ]
            ]
        ];

        $form_generator = new FormGenerator($form_generator_array, 'add');
        $form_generator->extract();
        return $form_generator->getOutput();
    }
}
