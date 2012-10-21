{def $toppost = fetch(content, tree, hash(parent_node_id, 2, sort_by, array('published', 0), limit, 5, class_filter_type, 'include', class_filter_array, array('blog_post')))}

{foreach $toppost as $key => $tp}
<div class='tag_embeded{if $key} withtopmargin{/if}'>
	
{node_view_gui content_node=$tp view='full' isembed=true()}

</div>
{/foreach}