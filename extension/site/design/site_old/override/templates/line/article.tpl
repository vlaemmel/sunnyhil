{* Article - Line view *}
{def $out = ''}

<div class="content-view-line">
    <div class="class-article float-break">

    <h2><a href={$node.url_alias|ezurl}>{$node.data_map.title.content|wash}</a></h2>

    {section show=$node.data_map.image.has_content}
        <div class="attribute-image">
            {attribute_view_gui image_class=articlethumbnail href=$node.url_alias|ezurl attribute=$node.data_map.image}
        </div>
    {/section}

    {section show=$node.data_map.intro.content.is_empty|not}
    <div class="attribute-short">
		{set $out=$node.data_map.intro.content.output.output_text|preg_replace('/,.*\\|/','')}
		{set $out=$out|preg_replace('/^[^,]*[a-z]([A-Z])/','$1')}
		{$out}
    </div>
    {/section}

    </div>
</div>