<?php
use fragments\widgets\Table;
use fragments\helpers\Html;



$m = \common\models\Blog::find()->where(['='=>['id'=>26]])->one();

?>
<section class='row button-row'>
    <?=Html::a('new blog','/blog/view', ['class'=>'btn btn-default success'])?>
    <section class='table-actions hidden'>
        <?=Html::a('remove all','/blog/remove-all', ['id'=>'remove-all', 'class'=>'btn btn-default alert', 'data-message' => 'are you sure you want to remove all the selected items?'])?>
        <?=Html::a('publish all','/blog/publish-all', ['id'=>'publish-all', 'class'=>'btn btn-default action', 'data-message' => 'are you sure you want to publish all selected items?'])?>
        <?=Html::a('unpublish all','/blog/unpublish-all', ['id'=>'unpublish-all', 'class'=>'btn btn-default action', 'data-message' => 'are you sure you want to unpublish all selected items?'])?>
    </section>
</section>

<section class='row'>
    <h2 class='table-header'>Blog items</h2>
<?=Table::widget([
    'modelProvider' => $modelProvider,
    'columns' => [
        'select' => [
            'header' => "<input class='select-all' type='checkbox'></input><label for='select-all'>select</label>",
            'options' => [
                'style' => [
                    'width' => '120px',
                ],
            ],
            'value' => function($model){
                return "<input type='checkbox' value='$model->id' class='row-select'/>";
            }
        ],
        'id',
        'title',
        'slug',
        'is_published' => [
            'header' => '',
            'options' => [
                'style' => [
                    'width' => '40px',
                    'text-align' => 'center',
                ]
            ],
            'value' => function($model) {
                return ($model->is_published == true) ? '<span class="label label-default success" success>published</span>' : '<span class="label label-default warning">not published</span>';
            }
        ],
        'category' => [
            'value' => function($model) {
                $category = $model->category;
                if($category){
                    return $category->title;
                } else {
                    return "(not set)";
                }
            }
        ],
        'created_at' => [
            'value' => function($model){
                return $model->date;
            }
        ],
        'view' => [
            'value' => function($model){
                $out = Html::a('<i class="material-icons label-action">open_in_new</i>', "/blog/view?id=$model->id", ['target'=>'_blank']);
                $out .= Html::a('<i class="material-icons label-action">close</i>', "/blog/remove?id=$model->id");
                return $out;
            }
        ]
    ],
])?>
</section>
<?php
$js = <<<JS
f(document).on('click', '.table.table-default td', function(e){
    select = f(this.parentNode).findOne('.row-select');
    if(select.exists()){
        select.element().checked = (select.element().checked ? true : false);
    }
});
f(document).on('change', '.row-select', function(e){
    table = f(f(this).parentsUntil('.table'));
    hasChecked = table.findAll('input[type="checkbox"]:checked');
    selectAll = table.findOne('.select-all');
    if(selectAll.exists()){
        if(hasChecked.length > 0){
            selectAll[0].checked = true;
        } else {
            selectAll[0].checked = false;
        }
    }

});
JS;
$this->registerJs($js);


?>
