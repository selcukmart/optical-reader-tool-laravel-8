@extends('layouts.front-page')
@section('content')
    <div class="row">
        <?php
        use OpticalMarkReader\OpticalReaderStats\OpticalReaderUserExamStats;

        if (!isset($operation_result_ok)) {
        ?>
        <div class="col-sm-6" style="margin-bottom: 15px;">
            <form action="{{ Request::url() }}" method="post" enctype="multipart/form-data" name="form1"
                  id="form1" class="form-horizontal">
                <div class="form-body">
                    <?php
                    if (isset($OpticalReaderManager)) {
                    if (!$OpticalReaderManager->isResult()) {
                    ?>
                    <div class="alert alert-danger"><?= $OpticalReaderManager->getErrorMessage() ?></div>
                    <?php
                    }
                    }
                    if (isset($error_message)) {
                    ?>
                    <div class="alert alert-danger"><?= $error_message ?></div>
                    <?php
                    }
                    echo $controller->getForm();
                    //include __DIR__ . '/omr-form-upload-form-generator.php';
                    ?>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-sm-12">
                            <button name="save"
                                    style="margin-top: 10px;" type="submit"
                                    class="btn btn-success btn-block btn-lg"><i
                                    class="fa fa-cloud-upload"></i> Çıktıyı Sisteme Yükle
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
        } else {
        ?>
        <div class="col-sm-6" style="margin-bottom: 15px;">
            <a style="margin-bottom: 15px;" href="<?= $editFormAction ?>" class="btn btn-outline btn-info"> <i
                    class="fa fa-angle-double-left" aria-hidden="true"></i> <?= $tool_object->getTitle() ?> Ana
                Ekranına Geri Dön</a>
            <?php
            if ($OpticalReaderManager->isResult()) {
            $OpticalReaderUserExamStats = new OpticalReaderUserExamStats($OpticalReaderManager->getExamId());
            $OpticalReaderUserExamStats->generate();
            ?>
            <div class="alert alert-success">Sınavınız başarıyla içeriye alınmıştır.<br>
                <a class="btn btn-outline btn-info" href="/exams">Buraya
                    tıklayarak
                    sınav bilgilerinize erişim
                    sağlayabilirsiniz.</a></div>
            <?php
            } else {
            ?>
            <div class="alert alert-danger"> <?= $OpticalReaderManager->getErrorMessage() ?></div>
            <?php
            } ?>
        </div>
        <?php
        }
        ?>
    </div>
@stop
