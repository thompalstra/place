f(document).on('click', '.nav.nav-item', function(e){
    f(this).siblings('.nav-dropdown').each(function(e){
        f(this).attr('toggled', 'false');
        f(this).find('[toggled="true"]').each(function(e){
            f(this).attr('toggled', 'false');
        });
    });
},true);
f(document).on('click', '.dropdown', function(e){
    ul = f(this).findOne('ul');
    if(ul.attr('toggled') == 'true'){
        f(this).removeClass('open');
    } else {
        f(this).addClass('open');
    }
});
f(document).on('mousedown', '.fileinput > .trigger', function(e){
    input = f(this.parentNode).findOne('input');
    input[0].click();
    console.log('fileinput trigger');
})
f(document).on('change', '.fileinput > input', function(e){
    fullPath = this.value;
    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
    var filename = fullPath.substring(startIndex);
    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
        filename = filename.substring(1);
    }
    label = f(this.parentNode).findOne('label');
    clear = f(this.parentNode).findOne('.clear');
    if(filename){
        label.element().innerText = filename;
        f(this.parentNode).addClass('success');
        clear.style('display', 'inline-block');
    } else {
        label.element().innerText = f(this).attr('data-placeholder');
        f(this.parentNode).removeClass('success');
        clear.hide();
    }
    console.log('fileinput input');
});

f(document).on('click', '.fileinput > .clear', function(e){
    e.preventDefault();
    input = f(this.parentNode).findOne('input');
    input[0].value = "";
    input.trigger('change');
});

f(document).on('change', '.table .select-all', function(e){
    checked = this.checked;
    table = f(this).parentsUntil('.table');
    f(table).findAll('.row-select').each(function(e){
        this.checked = checked;
    });

    if(checked){
        f('.table-actions').show();
    } else {
        f('.table-actions').hide();
    }
});

f(document).on('change', '.table .row-select', function(e){
    hasSelected = f(this.parentNode).find('.row-select:checked');
    table = f(this).parentsUntil('.table');
    selectAll = f(table).findOne('.select-all');
    selectAll[0].checked = (hasSelected.length > 0);

    if(selectAll[0].checked){
        f('.table-actions').show();
    } else {
        f('.table-actions').hide();
    }
});

f(document).on('click', '.table td', function(e){
    select = f(this.parentNode).findOne('.row-select');
    select[0].checked = !select[0].checked;
    select.trigger('change');
}, true);

f(document).on('click', '.table-actions > a', function(e){
    e.preventDefault();
    href = f(this).attr('href');
    values = [];
    f('.row-select:checked').each(function(e){
        values.push(this.value);
    });
    f.request.send({
        method: 'get',
        type: 'json',
        url: href+'?collection='+JSON.stringify(values),
        done: function(response){
            if(response.result == true){
                f.reload({
                    container:'.table',
                    url:location.href,
                    done: function(response){
                        for(var i in values){
                            cb = f('.row-select[value="'+values[i]+'"]');
                            if(cb.exists()){
                                cb[0].checked = true;
                            }
                        }
                    }
                });
            }
        }
    })
});

f(document).on('click', '.tagmanager .tag > .remove', function(e){
    this.parentNode.remove();
});
f(document).on('keydown', '.tagmanager > input', function(e){
    if(e.keyCode == 13){
        e.preventDefault();
        min = f(this.parentNode).attr('min-length');
        max = f(this.parentNode).attr('max-length');
        value = this.value;
        if(min != undefined && max != undefined){
            // check min length
            if(value.length < min){
                alert('Value is too short');
                return;
            }
            // check max length
            if(value.length > max){
                alert('Value is too long');
                return;
            }
        }
        inner = f(this.parentNode).findOne('.inner');
        template = f(this.parentNode).findOne('[template="true"]');
        if(template.exists() && inner.exists()){
            newItem = template[0].cloneNode(true);
            newItem.removeAttribute('template');
            newItemInput = newItem.querySelector('input')
            newItemInput.setAttribute('value', value);
            newItemInput.removeAttribute('disabled');
            newItemLabel = newItem.querySelector('.label-value');
            newItemLabel.innerHTML = value;

            inner[0].appendChild(newItem);
            this.value = '';
            this.focus();
        }
    }
});
