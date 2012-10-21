{* Person - Line view *}

<div class="content-view-line">
    <div class="class-person">

    <h2><a href={$node.url_alias|ezurl}>{$node.name|wash}</a> ( {attribute_view_gui attribute=$node.data_map.job_title} )</h2>

    {section show=$node.data_map.picture.content}
        <div class="attribute-image">
            {attribute_view_gui attribute=$node.data_map.picture.content.data_map.image alignment=right image_class=small href=$node.url_alias|ezurl}
        </div>
    {/section}

    <div class="attribute-matrix">
    <h3>{"Contact information"|i18n("design/base")}</h3>
        {attribute_view_gui attribute=$node.data_map.contact_information}
    </div>
    </div>

    <div class="break"></div>
</div>
