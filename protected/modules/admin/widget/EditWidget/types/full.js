tinymce.init({
    editor_selector : "<?=$editorSelector?>",
  //  plugins : "paste, table, -gismap, autolink", //-cmsbuttons
    plugins : "hr, colorpicker, emoticons, code, visualchars, wordcount, link, -ymap, image, autolink,lists, pagebreak,layer,table, save,insertdatetime, preview,media, searchreplace,print, contextmenu,paste,directionality,fullscreen,noneditable, visualchars,nonbreaking,template",


    table_class_list: [
        {title: 'Без класса', value: ''},
        
        {title: 'Стиль рамка + зебра', value: 'table-bordered table-striped'},
        {title: 'Стиль рамка + подсвечивание', value: 'table-bordered table-hover'},
        {title: 'Стиль зебра + рамка + подсвечивание', value: 'table-bordered table-hover table-striped'},
        
        {title: 'Стиль "Зебра"', value: 'table table-striped'},
        {title: 'Стиль "Рамка"', value: 'table table-bordered'},
        {title: 'Стиль "Подсвечивание"', value: 'table table-hover'},
        {title: 'Стиль "Компактная таблица"', value: 'table table-condensed'},
    ],

    insert_width: 200,

   
    mode : "textareas",
    theme : "modern",
    language : "ru",

    height : <?=$height?>,
    menubar: "insert edit view table", /*format */
    image_advtab: true,
    menu : { // this is the complete default configuration
        insert : {title : 'Insert', items : 'link media image | template hr autolink insertdatetime'},
        edit   : {title : 'Edit'  , items : 'undo redo | cut copy selectall'},
        tools  : {title : 'Tools' , items : 'spellchecker code'},
        table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'},
        view   : {title : 'View'  , items : 'visualaid | code'},
    },


    contextmenu: "link image inserttable | cell row column deletetable",
    content_css: '<?=$assets?>/css/editor.css',
    toolbar1: "styleselect template | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent hr | table | code preview | link unlink | image | clearfix | removeformat | other cms_gallery_1 cms_gallery_2 ymap | <?php if(Yii::app()->params['adaptiveTemplate']) echo "makeResponsive"; ?> | tablecontrols | undo redo",
    
    


    templates: "/js/tiny_templates/template.php",

    style_formats: [

        {title: 'Абзац', block: 'p'},
        {title: 'Заголовок H3', block: 'h3'},
        {title: 'Заголовок H4', block: 'h4'},
        
        <?if(Yii::app()->params['adaptiveTemplate']): ?>
            {title: "Важная информация", block: "blockquote", "classes": "user_info_block_adaptive", wrapper: true},
        <?else:?>
            {title: "Важная информация", block: "blockquote", "classes": "user_info_block", wrapper: true},
        <?endif?>
        
        {title: "Свернутый текст", block: "div", "classes": "is_read_more", wrapper: true},

        <?if(Yii::app()->params['adaptiveTemplate']): ?>
        {title: 'Скрывать на планшетах', block: 'div', 'classes' : 'hidden_planshet', wrapper: true},
        {title: 'Скрывать на телефонах', block: 'div', 'classes' : 'hidden_telephone', wrapper: true},
        <?endif; ?>
        {title: 'div', block: 'div'},

/*        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'},*/
    ],
    setup: function(ed) {

        ed.addCommand('insertCFix', function() {

            ed.focus();
            tinymce.activeEditor.execCommand('mceInsertContent', false, "<div class='clearfix'></div>");

        });

        ed.addCommand('mceResp', function() {

            var div_class = 'table-responsive'
            var elem = ed.dom.getParent(ed.selection.getNode(), "table");

            if(elem==null){
                div_class = 'fluid_width_video';
                var elem = ed.selection.getNode();
            }

            content = $(elem)[0].outerHTML;
            if(!$(elem).parent().hasClass(div_class)){
                if($(elem).hasClass('mce-object-iframe') || $(elem).hasClass('mce-item-table')){
                    var newdiv = $('<div class="'+div_class+'">');
                    $(elem).after('<div class="'+div_class+'">' + content + '</div><p>&nbsp;</p>');
                    $(elem).remove();
                }
            }
            else{
               if($(elem).hasClass('mce-object-iframe') || $(elem).hasClass('mce-item-table')){
                    $(elem).parent().remove();
                    $(elem).remove();
                    ed.focus();
                    ed.selection.setContent(content);
               } 
            }

        });

        ed.addButton('makeResponsive', {
            title : 'Включить адаптивность для таблицы или видео',
            cmd : 'mceResp',
            label: 'Включить адаптивность',
            image: '<?php echo $assets; ?>/images/ico-responsible.png',
        });

        ed.addButton('clearfix', {
            title : 'Очистить поток (clearfix)',
            cmd : 'insertCFix',
            label: 'вставка clearfix',
            image: '<?php echo $assets; ?>/images/cf.jpg',
        });

        ed.addButton('tablesettings', {
            title : 'Таблицы',
            onclick : function(){
                tiny_togglePanel('toolbar2', 'tablesettings');
            }
        });

        ed.addButton('other', {
            title: 'Форма обратной связи',
            image: '<?php echo $assets; ?>/images/form.png',
            onclick: function() {
                var call_block = "{form_feedback}";
                var content = ed.getContent();
                if (content.indexOf(call_block) < 0) {
                    ed.focus();
                    ed.selection.setContent(call_block);
                } else {
                    ed.windowManager.alert("Вы уже вставили форму");
                }
            }
        });

        ed.addButton("cms_gallery_1", {
            title   : "Галерея (тип 1)",
            image: '<?php echo $assets; ?>/images/gallery1.png',
            onclick : function() {
                var call_block = "{simple_gallery}";
                var content = ed.getContent();
                if (content.indexOf(call_block) < 0 && content.indexOf("{gallery}") < 0) {
                    ed.focus();
                    ed.selection.setContent(call_block);
                } else
                    ed.windowManager.alert('Вы уже вставили галерею');
            }
        });

        ed.addButton("cms_gallery_2", {
            title : 'Галерея (тип 2)',
            image: '<?php echo $assets; ?>/images/gallery2.png',
            onclick : function() {
                var call_block = "{gallery}";
                var content = ed.getContent();
                var count = content.indexOf(call_block);
                if (count < 0 && content.indexOf("{simple_gallery}") < 0) {
                    ed.focus();
                    ed.selection.setContent(call_block);
                } else {
                    ed.windowManager.alert("Вы уже вставили галерею");
                }
            }
        });

        ed.addButton("cms_comments", {
            title : 'Комментарии',

            onclick : function() {
                var call_block = "{comments}";
                var content = ed.getContent();
                if (content.indexOf(call_block) > 0) {
                    ed.windowManager.alert("Вы уже вставили комментарии");
                } else {
                    ed.focus();
                    ed.selection.setContent(call_block);
                }
            }
        });

   },

});

tinymce.create('tinymce.plugins.YMap', {
    init : function(ed, url) {
        var dialog_url = '<?=$ymapDialog; ?>';
        ed.addCommand('mceYMap', function() {
            ed.windowManager.open({
            	title: "Вставить Яндекс.Карту на сайт",
                file : dialog_url,
                width : 700,
                height : 500,
                inline : 1,
                buttons:[{
                    text:"Insert", 
                    onclick:function(){

                        var g2block = "{ymap}";
                        var content = ed.getContent();
                        if (content.indexOf(g2block) > 0) {
                            //ed.windowManager.alert("Вы уже вставили блок с Яндекс.Картой");
                            parent.tinyMCE.activeEditor.windowManager.close(window);
                        } else {
                            ed.focus();
                            ed.selection.setContent(g2block);
                            parent.tinyMCE.activeEditor.windowManager.close(window);
                        }
                    }},{
                        text:"Close",onclick:"close"}]}, 
                    {
            });
        });
        ed.addButton('ymap', {
            image: '<?=$assets?>/images/ymaps.png',
            title : 'Яндекс.Карта',
            cmd : 'mceYMap'
        });
    }
});
tinymce.PluginManager.add('ymap', tinymce.plugins.YMap);
