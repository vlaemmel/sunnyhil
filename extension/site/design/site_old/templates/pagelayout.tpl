{foreach $module_result.content_info.persistent_variable as $k => $v}
	{if $k|contains('comform_')}{array($k, $v)|store_sess}{/if}
{/foreach}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">
<head>

{def $basket_is_empty   = cond( $current_user.is_logged_in, fetch( shop, basket ).is_empty, 1 )
$user_hash         = concat( $current_user.role_id_list|implode( ',' ), ',', $current_user.limited_assignment_value_list|implode( ',' ) )}
{if is_set( $extra_cache_key )|not}
{def $extra_cache_key = ''}
{/if}
{cache-block keys=array( $module_result.uri, $basket_is_empty, $current_user.contentobject_id, $extra_cache_key )}
{def $pagedata = ezpagedata()
$pagestyle = $pagedata.css_classes
$locales  = fetch( 'content', 'translation_list' )
$pagedesign  = $pagedata.template_look
$current_node_id  = $pagedata.node_id}
{include uri='design:page_head.tpl'}
{include uri='design:page_head_style.tpl'}
{include uri='design:page_head_script.tpl'}

<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>

<script type='text/javascript'>

var addthis_pub="amandabroadfoot";var addthis_brand="Amanda Broadfoot";var addthis_header_color="#ffffff";var addthis_header_background="#2F4165";
var addthis_share ='';




</script>



</head>
<body>
<!-- Complete page area: START -->

<!-- Change between "sidemenu"/"nosidemenu" and "extrainfo"/"noextrainfo" to switch display of side columns on or off  -->
<div id="page" class="{$pagestyle}">

  {if and( is_set( $pagedata.persistent_variable.extra_template_list ), 
             $pagedata.persistent_variable.extra_template_list|count() )}
    {foreach $pagedata.persistent_variable.extra_template_list as $extra_template}
      {include uri=concat('design:extra/', $extra_template)}
    {/foreach}
  {/if}

  <!-- Toolbar area: START -->
  {if and( $pagedata.website_toolbar, $pagedata.is_edit|not)}
    {include uri='design:page_toolbar.tpl'}
  {/if}
  <!-- Toolbar area: END -->

  <!-- Top menu area: START -->
  {if $pagedata.top_menu}
    {include uri='design:page_topmenu.tpl'}
  {/if}
  <!-- Top menu area: END -->

  <!-- Header area: START -->
  {include uri='design:page_header.tpl'}
  <!-- Header area: END -->

  <!-- Path area: START -->

    {include uri='design:page_toppath.tpl'}

  <!-- Path area: END -->
  


  <!-- Columns area: START -->
  <div id="columns-position">
  <div id="columns" class="float-break">

    <!-- Side menu area: START -->
    {if $pagedata.left_menu}
        {include uri='design:page_leftmenu.tpl'}
    {/if}
    <!-- Side menu area: END -->
{/cache-block}
    <!-- Main area: START -->
    {include uri='design:page_mainarea.tpl'}
    <!-- Main area: END -->
{cache-block keys=array( $module_result.uri, $user_hash, $access_type.name, $extra_cache_key )}

    {if is_unset($pagedesign)}
        {def $pagedata   = ezpagedata()
             $pagedesign = $pagedata.template_look}
    {/if}

    <!-- Extra area: START -->
    {if $pagedata.extra_menu}
        {include uri='design:page_extramenu.tpl'}
    {/if}
    <!-- Extra area: END -->

  </div>
  </div>
  <!-- Columns area: END -->

  <!-- Footer area: START -->
  {include uri='design:page_footer.tpl'}
  <!-- Footer area: END -->

</div>
<!-- Complete page area: END -->

<!-- Footer script area: START -->
{include uri='design:page_footer_script.tpl'}
<!-- Footer script area: END -->

{/cache-block}

{* This comment will be replaced with actual debug report (if debug is on). *}
<!--DEBUG_REPORT-->

{literal}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12584655-1");
pageTracker._trackPageview();
} catch(err) {}</script>
{/literal}
</body>
</html>

