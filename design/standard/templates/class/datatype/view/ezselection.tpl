{* DO NOT EDIT THIS FILE! Use an override template instead. *}
<div class="block">
<label>{'Type'|i18n( 'design/standard/class/datatype' )}:</label>
    {section show=$class_attribute.data_int1}
        <p>{'Multiple choice'|i18n( 'design/standard/class/datatype' )}</p>
    {section-else}
        <p>{'Single choice'|i18n( 'design/standard/class/datatype' )}</p>
    {/section}
</div>

<div class="block">
<label>{'Options'|i18n( 'design/standard/class/datatype' )}:</label>
{section show=$class_attribute.content.options}
<table class="list" cellspacing="0">
{section var=Options loop=$class_attribute.content.options sequence=array( bglight, bgdark )}
<tr class="{$Options.sequence}"><td>{$Options.item.name|wash( xhtml )}</td></tr>
{/section}
</table>
{section-else}
<p>{'There are no options.'|i18n( 'design/standard/class/datatype' )}</p>
{/section}
</div>
