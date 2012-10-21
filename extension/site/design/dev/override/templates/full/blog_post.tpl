{* Blog post - Full view *}
{if is_set($isembed)|not}
{set scope=global persistent_variable=hash('left_menu', false(),
                                           'extra_menu', false(),
										   'keywords', '',
										   'site_title', $node.name,
										   'description', $node.data_map.body.content.output.output_text|html_shorten(100)|strip_tags)}
{/if}

<div class="class-blog extrainfo">
    <div class="columns-blog float-break">
        <div class="main-column-position">
            <div class="main-column float-break">
                <div class="border-box">
                <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
                <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

                        <div class="content-view-full">
                            <div class="class-blog-post float-break">
	
								<div class='sharebutton'><!-- AddThis Button BEGIN -->
								<a id="mysb_{$node.node_id}"></a>
								<script type="text/javascript">
								addthis.button("#mysb_{$node.node_id}", {ldelim}{rdelim}, {ldelim} url:"http://www.lifeisaspectrum.com{$node.url_alias|ezroot(no)}?article_id={$node.node_id}-{currentdate()}"{rdelim});
								</script>
								<!-- AddThis Button END --></div>

                                <div class="attribute-header">
                                    <h1><a name='anch_{$node.node_id}'>{$node.data_map.title.content|wash}</a></h1>
                                </div>
								
                                <div class="attribute-byline">
                                    <p class="date">{$node.data_map.publication_date.content.timestamp|l10n(shortdatetime)}</p>
                                    {*<p class="author">{$node.object.owner.name}</p>
                                    <p class="tags"> {"Tags:"|i18n("design/ezwebin/full/blog_post")}
                                         {foreach $node.data_map.tags.content.keywords as $keyword}
                                             <a href={concat( $node.parent.url_alias, "/(id)/", $node.parent.node_id, "/(tag)/", $keyword|rawurlencode )|ezroot} title="{$keyword}">{$keyword}</a>
                                             {delimiter}
                                               ,
                                             {/delimiter}
                                         {/foreach}
                                    </p>*}
                                </div>

                                <div class="attribute-body float-break">
                                    {attribute_view_gui attribute=$node.data_map.body}
                                </div>

                                {include uri='design:parts/related_content.tpl'}

                                {if $node.data_map.enable_comments.data_int}
                                <div class="attribute-comments">
	
								{*
                                    <a name="comments" id="comments"></a>
                                    <h3>{"Comments"|i18n("design/ezwebin/full/blog_post")}</h3>
                                    <div class="content-view-children">
                                        {foreach fetch( content, list, hash(class_filter_type, 'include', class_filter_array, array('comment'), parent_node_id, $node.node_id, sort_by, array('published', 1) ) ) as $comment}
                                            {node_view_gui view='line' content_node=$comment}
                                        {/foreach}
                                    </div>

                                   {if fetch( 'content', 'access', hash( 'access', 'create', 'contentobject', $node, contentclass_id, 'comment' ) )}
                                   <form method="post" action={"content/action"|ezroot}>
                                       <input type="hidden" name="ClassIdentifier" value="comment" />
                                       <input type="hidden" name="NodeID" value="{$node.object.main_node.node_id}" />
                                       <input type="hidden" name="ContentLanguageCode" value="{ezini( 'RegionalSettings', 'ContentObjectLocale', 'site.ini')}" />
                                       <input class="button new_comment" type="submit" name="NewButton" value="{'New comment'|i18n( 'design/ezwebin/full/article' )}" />
                                   </form>
                                   {else}
                                       {if ezmodule( 'user/register' )}
                                           <p>{'%login_link_startLog in%login_link_end or %create_link_startcreate a user account%create_link_end to comment.'|i18n( 'design/ezwebin/full/blog_post', , hash( '%login_link_start', concat( '<a href="', '/user/login'|ezroot(no), '">' ), '%login_link_end', '</a>', '%create_link_start', concat( '<a href="', "/user/register"|ezroot(no), '">' ), '%create_link_end', '</a>' ) )}</p>
                                       {else}
                                           <p>{'%login_link_startLog in%login_link_end to comment.'|i18n( 'design/ezwebin/article/comments', , hash( '%login_link_start', concat( '<a href="', '/user/login'|ezroot(no), '">' ), '%login_link_end', '</a>' ) )}</p>
                                       {/if}
                                   {/if}

								*}
								<h3>Reader Comments</h3>
								{attribute_view_gui attribute=$node.data_map.comments attribute_node=$node}
								
                                </div>
                                {/if}
                            </div>
                        </div>

                </div></div></div>
                <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
                </div>
            </div>
        </div>

        <div class="extrainfo-column-position">
            <div class="extrainfo-column">
                <div class="border-box">
                <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
                <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
                        {include uri='design:parts/blog/extra_info.tpl' used_node=$node.parent}
                </div></div></div>
                <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>