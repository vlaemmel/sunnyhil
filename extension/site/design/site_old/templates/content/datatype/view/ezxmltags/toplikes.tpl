{def $toppost = fetch(content, tree, hash(parent_node_id, 2, sort_by, array('published', 0), limit, $limit, attribute_filter, array(array('blog_post/like', '=', 0)) ))
	 $doneme = fetch(content, tree, hash(parent_node_id, 2, sort_by, array('published', 0), limit, 1, class_filter_type, 'include', class_filter_array, array('blog_post')))
	 $cc = 0}

<div class='tag_embeded'>
    <div class="border-box">
    <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
    <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
	
<h2>Things I Like</h2>
	
{foreach $toppost as $key => $thispost}
	{if $thispost.node_id|eq($doneme[0].node_id)}{continue}{/if}
	
	{if $cc|eq(5)}
	
	</div></div></div>
	<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
	</div>
	<div class='col_ad'>
	{literal}
	
	<script type="text/javascript"><!--
	google_ad_client = "pub-0205002563984353";
	/* 234x60, Homepage Column */
	google_ad_slot = "1138914729";
	google_ad_width = 234;
	google_ad_height = 60;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
	
	{/literal}
	</div>
	<div class="border-box">
    <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
    <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
	
	{/if}
	
	{node_view_gui content_node=$thispost view='short_line'}
	{set $cc=$cc|inc}
{/foreach}

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

</div>