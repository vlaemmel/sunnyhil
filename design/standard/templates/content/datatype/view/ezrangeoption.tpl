{* DO NOT EDIT THIS FILE! Use an override template instead. *}
<label>{$attribute.content.name}:</label>
<select name="eZOption[{$attribute.id}]">
{section var=Options loop=$attribute.content.option_list sequence=array( bglight, bgdark )}
<option value="{$Options.item.id}">{$Options.item.value}</option>
{/section}
</select>