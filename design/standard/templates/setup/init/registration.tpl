{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{*?template charset=latin1?*}

<div align="center">
  <h1>{"Site registration"|i18n("design/standard/setup/init")}</h1>
</div>

<p>
 {"If you want, you can register this installation by sending some information to eZ Systems. No confidential data will be transmitted and eZ Systems will not use or sell your details for unsolicited emails."|i18n("design/standard/setup/init")}
</p>

<table cellpadding="0" cellspacing="0" border="0" class="full">
<tr><th class="label">{"The registration email"|i18n("design/standard/setup/init")}:</th></tr>
<tr><td class="normal"><textarea class="box full" readonly="readonly" cols="60" rows="10">{$email_body}</textarea></td></tr>
</table>

<p>
 {"If you want, you can also add some comments, which will be included in the registration email."|i18n("design/standard/setup/init")}
</p>


<form method="post" action="{$script}">

<table cellpadding="0" cellspacing="0" border="0" class="full">
<tr><th class="label">{"Comments"|i18n("design/standard/setup/init")}:</th></tr>
<tr><td class="normal"><textarea class="box full" name="eZSetupRegistrationComment" cols="60" rows="6">{$email_comments}</textarea></td></tr>
</table>

<br/>
<blockquote class="note">
<p>
 <b>{"Note"|i18n("design/standard/setup/init")}:</b>
 {"Sending out the email and generating your site will take about 10 to 30 seconds depending on your machine. Please wait until the next page loads. Clicking the button again will only send out duplicate emails, and may corrupt the installation."|i18n("design/standard/setup/init")}
</p>
</blockquote>
<br/>

<div align="right">
<input type="checkbox" name="eZSetupSendRegistration" {section show=$send_registration}checked="checked"{/section}value="checked" id="ez_reg_me" /><label class="checkbox" for="ez_reg_me">{"Send registration"|i18n("design/standard/setup/init")}</label>
</div>

  <div class="buttonblock" align="right">
  {include uri='design:setup/init/navigation.tpl'}
{*    <input class="button" type="submit" name="eZSetupSkipRegistration" value="{'Skip Registration'|i18n('design/standard/setup/init')} &gt;" />
    <input class="defaultbutton" type="submit" name="eZSetupSendRegistration" value="{'Send Registration'|i18n('design/standard/setup/init')} &gt;" /> *}
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
