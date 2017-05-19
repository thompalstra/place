<?php
use fragments\helpers\Html;
use fragments\widgets\Form;
?>
<?php $form = Form::begin([
    'id' => 'form-edit-file',
]);?>
<section style='height: 100px;'>
    <h4 style='height: 50px; margin: 0; padding: 0;'><?=$fileInfo->path?></h4>
    <?=Html::button('Update', ['type'=>'submit', 'class'=>'btn btn-default action'])?>
    <?=Html::a('back', "/files/index?path=$fileInfo->directory", ['class' => 'btn btn-default default'])?>
</section>
<section style='height: calc(100% - 166px);'>
<textarea name='File[new_content]' class='content' style='width: 100%; height: 100%; border: 0; background-color: #eee; color: black; padding: 10px;'>
<?=file_get_contents($fileInfo->path, true)?>
</textarea>

</section>
<?php Form::end(); ?>
<?php
$js = <<<JS

f(document).on('keydown', '.content', function(e){
    if(e.keyCode == 9){
        e.preventDefault();
        document.execCommand('insertHTML', false, '&#009');
        console.log('spaces');

    } else if ( e.keyCode == 13 && !e.shiftKey ){
        e.preventDefault();
        document.execCommand('insertHTML', true, "&#010");
        console.log('keydown');
    }
});

JS;
$this->registerJs($js);

?>
