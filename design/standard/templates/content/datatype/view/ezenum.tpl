{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{section var=Elements loop=$attribute.content.enumobject_list sequence=array( bglight, bgdark )}
{$Elements.item.enumelement|wash( xhtml )}<br />
{/section}