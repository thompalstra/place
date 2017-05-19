<?php
use fragments\widgets\BreadCrumb;
use common\models\File;
?>
<section class='file file-container'>
    <h4><?=BreadCrumb::widget([
            'items' => File::generateBreadCrumbItems($path),
        ])?></h4>
    <section class='toolbar' item-selected="false">
        <ul>
            <li class='add' toggle="true" toggle-for="#add-menu"><i class="icon material-icons">add</i> add
                <ul id="add-menu" toggled="true">
                    <li id='add-file'><i class="icon material-icons">insert_drive_file</i> file</li>
                    <li id='add-dir'><i class="icon material-icons">folder</i> folder</li>
                </ul>
            </li>
            <li class='view'><i class="icon material-icons">remove_red_eye</i> view</li>
            <li class='remove'><i class="icon material-icons">clear</i> remove</li>
            <li class='rename'>
                <i class="icon material-icons">text_fields</i> rename
                <!-- <span style='display: none'>
                    <input type='text'/>
                </span> -->
            </li>
        </ul>
    </section>
    <section class='inner'>
        <?php $path = ($path == '/') ? '' : $path;
        ?>
<?php foreach($contents as $content) { $dataPath = $path.$content['name']; ?>
    <div class='item <?=$content['type']?>' data-path="<?=$content['path']?>">
        <label class='name'><?=$content['name']?></label>
        <input class='filename' type="text" value="<?=$content['name']?>">
    </div>
<?php } ?>
    </section>
</section>


<?php
$js = <<<JS
f(document).on('click', '.file.file-container .toolbar[item-selected="true"] .rename', function(e){
    f(edit.currentItems[0]).findOne('.name').hide();
    input = f(edit.currentItems[0]).findOne('.filename')
    input.show();
    input[0].focus();
});
f(document).on('keyup', '.file.file-container .toolbar[item-selected="true"] + .inner .item:not([new="true"]) .filename', function(e){
    if(e.keyCode == 13){
        el = f(this);
        console.log(el);
        item = el.parentsUntil('.item')
        console.log(item);
        dataPath = f(item).attr('data-path');
        console.log(dataPath);
        e.preventDefault();
        f.request.send({
            url: '/files/rename',
            type: 'json',
            method: 'post',
            data: {
                name: this.value,
                path: dataPath,
            },
            done: function(resp){
                if(resp.result == true){
                    f.reload({
                        container: '.file.file-container',
                        done: function(r){
                            edit = new fileEdit();
                        }
                    });
                } else {
                    alert(resp.message);
                }
            }
        })
    }
});


f(document).on('dblclick', '.file.file-container .item.dir', function(e){
    e.preventDefault();
    url = '/files/index?path='+f(this).attr('data-path');
    location.href = url;
});
f(document).on('dblclick', '.file.file-container .item.return', function(e){
    e.preventDefault();
    url = '/files/index?path='+f(this).attr('data-path');
    location.href = url;
});

f(document).on('click', '#add-dir', function(e){

    edit.inner.findAll('[new="true"]').each(function(e){
        this.remove();
    })

    newElement = f(templateDir);
    edit.inner.appendChild(newElement);
    edit.select(newElement, e);
});
f(document).on('click', '#add-file', function(e){

    edit.inner.findAll('[new="true"]').each(function(e){
        this.remove();
    })

    newElement = f(templateFile);
    edit.inner.appendChild(newElement);
    edit.select(newElement, e);
});

f(document).on('click', '.file.file-container .item:not(.return)', function(e){
    if(e.target.tagName !== 'INPUT'){
        edit.select(this, e);
    }

});

f(document).on('click', '.file.file-container .toolbar[item-selected="true"] .remove', function(e){
    c = confirm("Are you sure you want to delete these item(s)?");
    if(c == true){
        items = edit.getSelectedPaths();
        url = '/files/remove';
        f.request.send({
            url: url,
            method: 'post',
            type: 'json',
            data : {data: items},
            done: function(resp){
                if(resp.result == true){
                    f.reload({
                        container: '.file.file-container',
                        done: function(r){
                            alert(resp.message);
                            edit = new fileEdit();
                        }
                    });
                } else {
                    alert(resp.message);
                }
            }
        })
    }
});
f(document).on('click', '.toolbar .view', function(e){
    url = '/files/view?file='+encodeURI(f(edit.currentItems[0]).attr('data-path'));
    location.href = url;
});
f(document).on('keydown', '.item[new="true"] .filename', function(e){
    if(e.keyCode == 13){
        item = f(f(this).parentsUntil('.item'));
        path = item.attr('data-path');
        name = this.value;
        type = item.attr('data-type');
        f.request.send({
            url: '/files/create',
            method: 'post',
            type: 'json',
            data: {
                    path: path,
                    name: name,
                    type: type,
            },
            done: function(resp){
                if(resp.result == true){
                    f.reload({
                        container: ".file.file-container",
                        done: function(r){
                            edit = new fileEdit();
                        }
                    })
                }
            }
        })
    }
});

templateDir = "<div class='item dir' new='true' data-type='dir' type='dir' data-path='$path' ><input class='filename' type='text'></div>";
templateFile = "<div class='item file' new='true' data-type='file' type='file' data-path='$path' ><input class='filename' type='text'></div>";

function fileEdit(){
    this.currentItems = [];
    this.toolbar = f('.file.file-container > .toolbar');
    this.inner = f('.file.file-container > .inner');
    this.getSelectedPaths = function(){
        items = [];
        this.inner.findAll('[selected="true"]').each(function(e){
            items.push(encodeURIComponent(this.getAttribute('data-path')));
        });
        return items;
    }
    this.select = function(el, e){
        this.inner.findAll('.filename').each(function(e){
            f(this).hide();
        });

        this.inner.findAll('.name').each(function(e){
            f(this).show();
        });

        if(e.ctrlKey){
            isSelected = false;
            for(var i in this.currentItems){
                if(this.currentItems[i] == el){
                    isSelected = i;
                    break;
                }
            }
            if(isSelected != false){
                this.currentItems[isSelected].removeAttribute('selected');
                delete this.currentItems[isSelected];
            } else {
                el.setAttribute('selected', 'true');
                this.currentItems.push(el);
            }
        } else {
            if(this.currentItems != [] && this.currentItems[0] == el){
                this.currentItems[0].removeAttribute('selected');
                this.currentItems = [];
            } else {
                this.currentItems = [];
                this.inner.findAll('[selected="true"]').each(function(e){
                    this.removeAttribute('selected');
                });
                this.currentItems.push(el);
                el.setAttribute('selected', 'true');
            }
        }
        if(this.currentItems.length > 0){

            if(this.currentItems.length == 1){
                type = f(this.currentItems[0]).hasClass('file') ? 'file' : 'dir';
                this.toolbar.attr('type-selected', type);
            } else {
                this.toolbar.attr('type-selected', '');
            }
            this.toolbar.attr('item-selected', 'true');
        } else {
            this.toolbar.attr('item-selected', 'false');
        }
    }
}

edit = new fileEdit();

JS;
$this->registerJs($js);
?>
