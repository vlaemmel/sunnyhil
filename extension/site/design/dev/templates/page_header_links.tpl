<div id="links">
    <ul>
        {if $pagedesign.data_map.tag_cloud_url.data_text|ne('')}
            {if $pagedesign.data_map.tag_cloud_url.content|eq('')}
            <li id="tagcloud"><a href={concat("/content/view/tagcloud/", $pagedata.root_node)|ezroot} title="{$pagedesign.data_map.tag_cloud_url.data_text|wash}">{$pagedesign.data_map.tag_cloud_url.data_text|wash}</a></li>
            {else}
            <li id="tagcloud"><a href={$pagedesign.data_map.tag_cloud_url.content|ezroot} title="{$pagedesign.data_map.tag_cloud_url.data_text|wash}">{$pagedesign.data_map.tag_cloud_url.data_text|wash}</a></li>
            {/if}
        {/if}
        {if $pagedesign.data_map.site_map_url.data_text|ne('')}
            {if $pagedesign.data_map.site_map_url.content|eq('')}
            <li id="sitemap"><a href={concat("/content/view/sitemap/", $pagedata.root_node)|ezroot} title="{$pagedesign.data_map.site_map_url.data_text|wash}">{$pagedesign.data_map.site_map_url.data_text|wash}</a></li>
            {else}
            <li id="sitemap"><a href={$pagedesign.data_map.site_map_url.content|ezroot} title="{$pagedesign.data_map.site_map_url.data_text|wash}">{$pagedesign.data_map.site_map_url.data_text|wash}</a></li>
            {/if}
        {/if}
        {if $basket_is_empty|not()}
        <li id="shoppingbasket"><a href={"/shop/basket/"|ezroot} title="{$pagedesign.data_map.shopping_basket_label.data_text|wash}">{$pagedesign.data_map.shopping_basket_label.data_text|wash}</a></li>
       {/if}
	<li id="newrss"><a href={"/rss/feed/blog/"|ezroot} title="RSS"><img src="/extension/site/design/site/images/RSS.png"/>RSS</a></li>
    {if $current_user.is_logged_in}
        {if $pagedesign.data_map.my_profile_label.has_content}
        <li id="myprofile"><a href={"/user/edit/"|ezroot} title="{$pagedesign.data_map.my_profile_label.data_text|wash}">{$pagedesign.data_map.my_profile_label.data_text|wash}</a></li>
        {/if}
        {if $pagedesign.data_map.logout_label.has_content}
        <li id="logout"><a href={"/user/logout"|ezroot} title="{$pagedesign.data_map.logout_label.data_text|wash}">{$pagedesign.data_map.logout_label.data_text|wash} ( {$current_user.contentobject.name|wash} )</a></li>
        {/if}
    {else}
        {if and( $pagedesign.data_map.register_user_label.has_content, ezmodule( 'user/register' ) )}
        <li id="registeruser"><a href={"/user/register"|ezroot} title="{$pagedesign.data_map.register_user_label.data_text|wash}">{$pagedesign.data_map.register_user_label.data_text|wash}</a></li>
        {/if}
        {if $pagedesign.data_map.login_label.has_content}
        <li id="login"><a href={"/user/login"|ezroot} title="{$pagedesign.data_map.login_label.data_text|wash}">{$pagedesign.data_map.login_label.data_text|wash}</a></li>
        {/if}
    {/if}

    {if $pagedesign.can_edit}
        <li id="sitesettings"><a href={concat( "/content/edit/", $pagedesign.id, "/a" )|ezroot} title="{$pagedesign.data_map.site_settings_label.data_text|wash}">{$pagedesign.data_map.site_settings_label.data_text|wash}</a></li>
    {/if}
    </ul>
</div>