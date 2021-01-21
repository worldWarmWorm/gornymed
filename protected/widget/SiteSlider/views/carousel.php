<ul id="site-slider">
    <?foreach($slides as $slide):?>
    <li>
        <div class="img">
            <a href="<?=$slide->link?>"><img src="<?=$slide->src?>" alt="<?=$slide->title?>" title="<?=$slide->title?>" /></a>
        </div>
        <div class="title">
            <a href="<?=$slide->link?>" title="<?=$slide->title?>"><?=$slide->title?></a>
        </div>
    </li>
    <?endforeach?>
</ul>
<script type="text/javascript">
    $(function() {
        $('#site-slider').jcarousel();
    });
</script>
