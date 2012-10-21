{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{def $matches=array()}
{section show=array('html', 'html-inline')|contains($classification)|not}
<pre{section show=ne($classification|trim,'')} class="{$classification|wash}"{/section}>{$content|wash(xhtml)}</pre>
{section-else}
{if $classification|eq('html-inline')}<div class='inline'>{/if}
	{foreach $content|preg_match("/[href|src]=\"([^\"]*)\"/")[1] as $match}
		{set $content = $content|explode($match)|implode($match|htmlspecialchars)}
	{/foreach}
	{foreach $content|preg_match("/[href|src]=\'([^\']*)\'/")[1] as $match}
		{set $content = $content|explode($match)|implode($match|htmlspecialchars)}
	{/foreach}
{$content}
{if $classification|eq('html-inline')}</div>{/if}
{/section}
