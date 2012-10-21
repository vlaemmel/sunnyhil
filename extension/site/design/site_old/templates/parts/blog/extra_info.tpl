{def $kc = 0
}
{if $#node.node_id|ne(2)}
<div class="attribute-description">
    {attribute_view_gui attribute=$used_node.object.data_map.description}
</div>
{/if}

{def $infoboxes = fetch( 'content', 'list', hash(	'parent_node_id', $used_node.node_id,
						'sort_by', $used_node.sort_array,
							'class_filter_type', 'include',
							'class_filter_array', array('infobox') ) )
}

{if count($infoboxes)}
	{foreach $infoboxes as $ib}
		{node_view_gui content_node=$ib view='infobox'}
	{/foreach}
{/if}


{*
{if $#node.node_id|eq(2)}
<div class="attribute-comments">
    <h1>Tweet Face</h1>

	<div id="main">

	<div id="feedWidget">
		<div id="activeTab" class='hover'>
	    	<!-- The name of the current tab is inserted here -->
	    </div>

	    <div class="line"></div>

	    <div id="tabContent">
	    	<!-- The feed items are inserted here -->
	    </div>
	</div>

	<div class="shadow"></div>
	<div class='sm_bs'>
	<a href='http://www.facebook.com/amanda.broadfoot'><img width='144px' src='http://static.ak.fbcdn.net/rsrc.php/z1M25/hash/5u84f48n.gif'/></a>
	<a href="http://twitter.com/LifeIsASpectrum"><img width='144px' src="http://www.twitterbuttons.net/images/ff9%20copy.jpg"/></a>

	<a href="http://thesitsgirls.com/" ><img src="http://i515.photobucket.com/albums/t357/sitsgirls/SS_150x150_button.png"></a>
	<!--Begin MLM logo link-->

	<a href="http://tallahassee.momslikeme.com"><img width='144' src="http://www.tallahassee.com/graphics/MLM_facebook_icon.jpg" border=0 /><br /></a>

	<!--End MLM logo link-->
	
	</div>
</div>
{/if}
*}

<div class="attribute-comments">
    <h1>Comments</h1>
    <ul>
	{def $newcomms = fetch(comment, comment_list, hash(contentobject_id, 0, language_id, 2, sort_field, 'created', sort_order, 'desc', offset, 0, length, 15))
	     $cc = 0
		 $tempnode = array()}

	{foreach $newcomms as $com}	
		{set $tempnode=fetch(content, object, hash(object_id, $com.contentobject_id)).main_node}
		<li><a href={$tempnode.url_alias|ezroot}>{$com.name} <span class='greyme'>on</span> {$tempnode.name|wash}</a></li>
		{set $cc=$cc|inc}
		{if $cc|ge(5)}{break}{/if}
	{/foreach}
	{if $newcomms|count|lt(5)}
    {foreach fetch( content, tree, hash(parent_node_id, 2, class_filter_type, 'include', class_filter_array, array('comment'), limit, sub(5, $cc), sort_by, array('published', false())) ) as $com}
        <li><a href={$com.parent.url_alias|ezroot}>{$com.data_map.author.content} <span class='greyme'>on</span> {$com.parent.name|wash}</a></li>
    {/foreach}
	{/if}
    </ul>
</div>

<div class="attribute-comments">
    <h1>News</h1>
    <ul>
    {foreach fetch( content, list, hash(parent_node_id, 382, class_filter_type, 'include', class_filter_array, array('article','link'), limit, 5, sort_by, array('published', false())) ) as $com}
        <li><a href={$com.url_alias|ezroot}><span class='greyme'>{$com.object.published|datetime('custom', '%m/%d/%Y')}</span> {$com.name|wash}</a></li>
    {/foreach}
    </ul>
</div>

<div class="attribute-tags">
    <h1>{"Tags"|i18n("design/ezwebin/blog/extra_info")}</h1>
    <ul>
    {foreach ezkeywordlist( 'blog_post', $used_node.node_id ) as $keyword}
		{set $kc = fetch( 'content', 'keyword_count', hash( 'alphabet', $keyword.keyword, 'classid', 'blog_post','parent_node_id', $used_node.node_id ) )}
        {if $kc|gt(1)}<li><a href={concat( $used_node.url_alias, "/(tag)/", $keyword.keyword|rawurlencode )|ezurl} title="{$keyword.keyword}">{$keyword.keyword} ({$kc})</a></li>{/if}
    {/foreach}
    </ul>
</div>

<div class="attribute-archive">
    <h1>{"Archive"|i18n("design/ezwebin/blog/extra_info")}</h1>
    <ul>
    {foreach ezarchive( 'blog_post', $used_node.node_id ) as $archive}
        <li><a href={concat( $used_node.url_alias, "/(month)/", $archive.month, "/(year)/", $archive.year )|ezurl} title="">{$archive.timestamp|datetime( 'custom', '%F %Y' )}</a></li>
    {/foreach}
    </ul>
</div>

{include uri='design:parts/blog/calendar.tpl'}