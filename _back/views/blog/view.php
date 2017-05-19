<?php
use fragments\widgets\Form;
use fragments\helpers\Html;
use common\models\BlogCategory;
use common\models\BlogContent;

use fragments\widgets\Slidebox;
use fragments\widgets\TagManager;

$classList = BlogContent::getClassList();
$typeList = BlogContent::getTypeList();
?>



<section class='row' style='padding-bottom: 80px;'>
    <?=Form::status($status)?>
    <?php $form = Form::begin([
        'id'=>'form-edit-blog',
        'enctype' => 'multipart/form-data',
        ])?>
    <?=$form->field($model, 'title')->textInput()?>
    <?=$form->field($model, 'slug')->textInput()?>
    <?=$form->field($model, 'category_id')->select(BlogCategory::getList())?>
    <?=$form->field($model, 'is_published')->widget( Slidebox::className(),[] )?>
    <?=$form->field($model, 'tags[]')->widget( TagManager::className(), [
        'minLength' => 1,
        'maxLength' => 999,
    ] )?>
    <section id='content-collection' class='row'>
        <?php foreach($model->content as $content){
            echo $this->renderPartial('/back/views/blog/_content.php', [
                'content'=>$content,
                'classList' => BlogContent::getClassList(),
                'typeList' => BlogContent::getTypeList(),
            ]);
        } ?>
    </section>
    <div class='container fixed bottom fixed-button-row'>
        <div class='row button-row'>
            <?=Html::button(($model->newModel) ? 'save' : 'update',['type'=>'submit', 'class'=>'btn btn-default action'])?>
            <div class='dropdown dropdown-default default top' toggle="true" toggle-for="#add-content">
                <i class="material-icons icon" >add_box</i>add content
                <ul id="add-content" class='toolbar-actions' toggled="true">
                    <?php foreach(BlogContent::getClassList() as $class){ ?>
                        <li data-class="<?=$class?>"><?=$class?></li>
                    <?php } ?>
                </ul>
            </div>
            <?=Html::a('cancel','/blog/index', ['class'=>'btn btn-default default'])?>
        </div>
    </div>
    <?=Form::end()?>
</section>

<?php
$eTypeList = json_encode($typeList);
$eClassList = json_encode($classList);
// $special = "\r\n\r\n";
$js = <<<JS
var classList = $eClassList;
var typeList = $eTypeList;

f(document).on('keyup', '.content-preview', function(e){
    p = this.parentNode;
    content = f(p).findOne('.content-textarea');
    if(content.exists()){
        content.value(this.innerHTML);
    }

});
f(document).on('click', '.blog-content .close', function(e){
    this.parentNode.parentNode.parentNode.parentNode.remove();
});
f(document).on('click', '#add-content > li', function(e){
    e.preventDefault();
    i = "a" + Date.now();
    className = f(this).attr('data-class');

    elementString = f.request.send({
        url : '/blog/get-content?identifier='+i+"&class="+className,
        type: 'get',
        done: function(response){
            elem = f(response);
            f('#content-collection').appendChild(elem);
        }
    });
});
f(document).on('change', '.class-selector', function(e){
    p = this.parentNode.parentNode;
    p.className = 'blog-content ' + this.value;
});
f(document).on('click', '.toggle-code', function(e){
    if(this.parentNode.parentNode.parentNode.parentNode.getAttribute('display-type') == 'code'){
        this.parentNode.parentNode.parentNode.parentNode.setAttribute('display-type', 'preview');
        this.innerHTML = '<i class="material-icons icon pull-left">code</i>view code';
    } else {
        this.parentNode.parentNode.parentNode.parentNode.setAttribute('display-type', 'code');
        this.innerHTML = '<i class="material-icons icon pull-left">text_fields</i>view preview';
    }
    this.parentNode.setAttribute('toggled', 'true');
})
f(document).on('click', '.sort-up',function(e){
    p = this.parentNode.parentNode.parentNode.parentNode;
    previous = p.previousElementSibling;
    if(previous !== null){
        p.parentNode.insertBefore(p, previous);
    }
    this.parentNode.setAttribute('toggled', 'true');
});
f(document).on('click', '.sort-down',function(e){

    p = this.parentNode.parentNode.parentNode.parentNode;
    next = p.nextElementSibling;
    nextNext = next.nextElementSibling;
    if(nextNext !== null){
        p.parentNode.insertBefore(p, nextNext);
    }
    this.parentNode.setAttribute('toggled', 'true');
});
f(document).on('click', '.toolbar-toggle', function(e){
    p = this.parentNode;
    toolbar = f(p).findOne('.toolbar')[0];
    if(toolbar.getAttribute('data-state') == 'visible'){
        toolbar.setAttribute('data-state', 'hidden');
    } else {
        toolbar.setAttribute('data-state', 'visible');
    }
    this.parentNode.setAttribute('toggled', 'true');
});
f(document).on('keyup', '.content-preview', function(e){
    //keyDown[e.keyCode] = false;
});
f(document).on('keydown', '.content-preview', function(e){
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
f(document).on('click', '.blog-content-more', function(e){
    f(this).toggleClass('open');
});
JS;
$this->registerJs($js);
?>
