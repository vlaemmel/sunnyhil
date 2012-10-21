{def $global_layout_class = fetch( 'content', 'class', hash( 'class_id', 'global_layout' ) )
     $global_layout_object = $global_layout_class.object_list[0]}

<!-- ZONE CONTENT: START -->

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">
<div class="border-content">

{attribute_view_gui attribute=$global_layout_object.data_map.page}

<div class='skybox'>
	{literal}
	
	<script type="text/javascript"><!--
	google_ad_client = "pub-0205002563984353";
	/* 120x600, Subpage Sky High */
	google_ad_slot = "2234936554";
	google_ad_width = 120;
	google_ad_height = 600;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
	
	{/literal}
</div>

</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

<!-- ZONE CONTENT: END -->