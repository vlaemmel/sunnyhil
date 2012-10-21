  <div id="header-position">
  <div id="header" class="float-break">
    <div id="usermenu">  
      {include uri='design:page_header_links.tpl'}
    </div>
	<a href='/'><img id='rune_pic' src={"rune_small.jpg"|ezimage}/></a>
    {include uri='design:page_header_logo.tpl'}
    {if $pagedesign.data_map.tagline.has_content}
		<div id='tagline'>
        {attribute_view_gui attribute=$pagedesign.data_map.tagline} 
		</div>
    {/if}
    {include uri='design:page_header_searchbox.tpl'}
    
    <p class="hide"><a href="#main">{'Skip to main content'|i18n('design/ezwebin/pagelayout')}</a></p>
  </div>
  </div>