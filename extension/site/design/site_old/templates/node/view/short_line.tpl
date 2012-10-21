{* Blog post - Line view *}
<div class="content-view-line">
    <div class="class-blog-post float-break">
	
	{def $morelink=concat("...<a href='", $node.url_alias|ezroot(no), "'>read more</a>")}

    <div class="attribute-byline">
        <p class="date">{$node.data_map.publication_date.content.timestamp|datetime('custom', '%m/%d/%Y')}</p>
 {*       <p class="author">{$node.object.owner.name}</p>
        <p class="tags"> Tags: {foreach $node.data_map.tags.content.keywords as $keyword}
                                           <a href={concat( $node.parent.url_alias, "/(tag)/", $keyword|rawurlencode )|ezroot} title="{$keyword}">{$keyword}</a>
                                           {delimiter}
                                               ,
                                           {/delimiter}
                                     {/foreach}
        </p> *}
    </div>

    <div class="attribute-header">
        <h3><a href={$node.url_alias|ezroot} title="{$node.data_map.title.content|wash}">{$node.data_map.title.content|wash}</a></h3>
     </div>
 

{*
        <div class="attribute-body float-break">
            {$node.data_map.body.content.output.output_text|html_shorten(100, $morelink)}
        </div>

        {if $node.data_map.enable_comments.data_int}
        <div class="attribute-comments">
        <p>
        {def $comment_count = fetch( 'content', 'list_count', hash( 'parent_node_id', $node.node_id,
                                                                    'class_filter_type', 'include',
                                                                    'class_filter_array', array( 'comment' ) ) )}
        {if $comment_count|gt( 0 )}
            <a href={concat( $node.url_alias, "#comments" )|ezroot}>{"View comments"|i18n("design/ezwebin/line/blog_post")} ({$comment_count})</a>
        {else}
            <a href={concat( $node.url_alias, "#comments" )|ezroot}>{"Add comment"|i18n("design/ezwebin/line/blog_post")}</a>
        {/if}
        </p>
        </div>
        {/if}
*}
    </div>
</div>