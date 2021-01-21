tinymce.init({
    editor_selector : "<?=$editorSelector?>",
  //  plugins : "paste, table, -gismap, autolink", //-cmsbuttons
    plugins : "code, visualchars, wordcount, link, autolink, lists, media, contextmenu, visualchars,nonbreaking",



    insert_width: 200,
   
    mode : "textareas",
    theme : "modern",
    language : "ru",

    height : <?=$height?>,
    menubar: "", /*format */
    image_advtab: true,


    contextmenu: "",
    content_css: '<?php echo $assets; ?>/css/editor.css',
    toolbar1: "bold italic | link unlink | removeformat | code  |",




});


