{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{default node_name=$object.main_node.name node_url=$object.main_node.url_alias}<img src={"class_2.png"|ezimage} border="0" alt="{'Document'|i18n('design/standard/node/view')}" />&nbsp;{section show=$node_url}<a href={$node_url|ezurl}>{/section}{$node_name|wash}{section show=$node_url}</a>{/section}
{/default}