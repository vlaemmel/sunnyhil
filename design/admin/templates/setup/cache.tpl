{* Feedbacks. *}
{section show=$cache_cleared.content}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Content view cache was cleared'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.all}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'All caches were cleared'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.ini}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Ini file cache was cleared'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.template}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Template cache was cleared'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.static}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Static content cache was regenerated'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.list}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The following caches were cleared'|i18n( 'design/admin/setup/cache' )}:</h2>
        <ul>
        {section var=Caches loop=$cache_cleared.list}
            <li>{'%name was cleared'|i18n( 'design/admin/setup/cache',, hash( '%name', $Caches.item.name ) )}</li>
        {/section}
        </ul>
    </div>
{/section}




<form name="clearcacheform" method="post" action={"/setup/cache/"|ezurl}>

{* Clear caches window. *}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Clear caches'|i18n( 'design/admin/setup/cache' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list cache" cellspacing="0">

{* Template cache. *}
<tr class="bglight">
<th width="60%">{'Template overrides and compiled templates'|i18n( 'design/admin/setup/cache' )}:</th>
<td width="40%"><input class="button" type="submit" name="ClearTemplateCacheButton" value="{'Clear template caches'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will clear all the template override caches and the compiled templates. It may lead to slower site performance until the caches are recreated.'|i18n( 'design/admin/setup/cache' )}" /></td>
</tr>

{* Content cache. *}
<tr class="bgdark">
<th>{'Content views and template blocks'|i18n( 'design/admin/setup/cache' )}:</th>
<td><input class="button" type="submit" name="ClearContentCacheButton" value="{'Clear content caches'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will clear all caches that are related to either template views or cache blocks inside the pagelayout template. Use it if you have modified templates or if you have made changes inside a cache block.'|i18n( 'design/admin/setup/cache' )}"/></td>
</tr>

{* Configuration cache. *}
<tr class="bglight">
<th>{'Configuration (ini) caches'|i18n( 'design/admin/setup/cache' )}:</th>
<td><input class="button" type="submit" name="ClearINICacheButton" value="{'Clear Ini caches'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will clear all the configuration caches. Use it to force the system to re-read the configuration files if you have changed settings.'|i18n( 'design/admin/setup/cache' )}" /></td>
</tr>

{* All caches. *}
<tr class="bgdark">
<th>{'Everything'|i18n( 'design/admin/setup/cache' )}:</th>
<td><input class="button" type="submit" name="ClearAllCacheButton" value="{'Clear all caches'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will clear all the caches and may lead to slow site response times until the caches are recreated.'|i18n( 'design/admin/setup/cache' )}" /></td>
</tr>

</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>




{* Cache overview window. *}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Fine-grained cache control'|i18n( 'design/admin/setup/cache' )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/setup/cache' )}" onclick="ezjs_toggleCheckboxes( document.clearcacheform, 'CacheList[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/setup/cache' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/setup/cache' )}</th>
    <th>{'Path'|i18n( 'design/admin/setup/cache' )}</th>
</tr>
{section var=Caches loop=$cache_list sequence=array( bglight, bgdark )}

{* Checkbox *}
<tr class="{$Caches.sequence}">
{section show=$cache_enabled.list[$Caches.item.id]}
<td><input type="checkbox" name="CacheList[]" value="{$Caches.item.id}" title="{'Select the <%cache_name> for clearing.'|i18n( 'design/admin/setup/cache',, hash( '%cache_name', $Caches.item.name ) )|wash}" /></td>
{section-else}
<td><input type="checkbox" name="CacheList[]" value="{$Caches.item.id}" disabled="disabled" title="{'The <%cache_name> is disabled and thus it cannot be marked for clearing.'|i18n( 'design/admin/setup/cache',, hash( '%cache_name', $Caches.item.name ) )|wash}" /></td>
{/section}

{* Name *}
<td>{$Caches.item.name}&nbsp;</td>

{* Path *}
<td>{$Caches.item.path}&nbsp;</td>

</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="ClearCacheButton" value="{'Clear selected'|i18n( 'design/admin/setup/cache' )}" title="{'Clear the selected caches.'|i18n( 'design/admin/setup/cache' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>


{* Regenerate static cache window. *}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Static content cache'|i18n( 'design/admin/setup/cache' )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list cache" cellspacing="0">

{* Static content cache. *}
<tr class="bgdark">
<th width="60%">{'Regenerate static content cache'|i18n( 'design/admin/setup/cache' )}:</th>
<td width="40%"><input class="button" type="submit" name="RegenerateStaticCacheButton" value="{'Create new'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will regenerate all the static content caches that are configured. This action can take  some time depending on the specifications of the server and the number of locations that are configured to be statically cached. If you encounter time-out problems, use the &quot;bin/php/makestaticcache.php&quot; shell script.'|i18n( 'design/admin/setup/cache' )}" /></td>
</tr>

</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>
