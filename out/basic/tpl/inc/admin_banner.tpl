
<script type="text/javascript" src="[{ $oViewConf->getResourceUrl() }]jquery.min.js"></script>
<script type="text/javascript" src="[{ $oViewConf->getResourceUrl() }]jquery.event.drag.min.js"></script>

<div id="demoAdminLink" style="background:url('[{ $oViewConf->getImageUrl() }]admin_start.jpg');">
    <a href="#" onClick="$( '#demoAdminLink' ).css({ display:'none' })" class="closeAdminLink"></a>
    <a href="[{ $oViewConf->getBaseDir() }]admin/"  rel="nofollow" class="openAdminLink"></a>
</div>

<script type="text/javascript">
    $( "#demoAdminLink" ).bind( 'drag', function( event ){ var offset = $("#page").offset(); $( this ).css({ top:event.offsetY-offset.top, left:event.offsetX-offset.left }); });
</script>
