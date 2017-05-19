<?php
use fragments\helpers\Html;
use fragments\widgets\FileInput;
?>


<section class='blog-content <?=$content->class?>' display-type='preview' tab-index="0">
    <section class='row button-row toolbar'>
        <div class='dropdown dropdown-default default bottom' toggle="true" toggle-for="#toolbar-actions-<?=$content->id?>">
            <i class="material-icons icon">settings</i>
            <ul id="toolbar-actions-<?=$content->id?>" class='toolbar-actions' toggled="true">
                <li class='sort-up'><i class="material-icons icon pull-left">arrow_drop_up</i>move up</li>
                <li class='sort-down'><i class="material-icons icon pull-left">arrow_drop_down</i>move down</li>
                <li class='toggle-code'><i class="material-icons icon pull-left">code</i>toggle code</li>
                <li class='close'><i class="material-icons icon pull-left">close</i>remove</li>
            </ul>
        </div>
        <i class="material-icons btn btn-default default blog-content-more">more_vert</i>
        <?=Html::selectInput([
            'data' => $classList,
            'value' => $content->class,
            'options' => [
                'name' => "BlogContent[$content->id][class]",
                'class' => 'class-selector',
            ]
        ])?>
        <?=Html::selectInput([
            'data' => $typeList,
            'value' => $content->type,
            'options' => [
                'class' => 'type-selector',
                'name' => "BlogContent[$content->id][type]",
            ]
        ])?>
        <?=FileInput::widget([
            'value' => '',
            'inputOptions' => [
                'name' => "BlogContent[$content->id][image]",
                'type' => 'file',
            ],
        ])?>
    </section>
<?=Html::textarea([
    'value' => $content->content,
    'options' => [
        'rows' => 5,
        'name' => "BlogContent[$content->id][content]",
        'class' => 'content-textarea',
    ]
])?>
<div class='content-preview <?=$content->type?>' contenteditable="true"><?=$content->getContent()?></div>
</section>
