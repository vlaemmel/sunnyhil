{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{let site_url=ezini("SiteSettings","SiteURL")}
{set-block scope=root variable=subject}{"%siteurl new password"|i18n("design/standard/user/forgotpassword",,hash('%siteurl',$site_url))}{/set-block}
{"Your account information"|i18n('design/standard/user/forgotpassword')}
{"Email"|i18n('design/standard/user/forgotpassword')}: {$user.email}

{section show=$link}
{"Click here to get new password"|i18n('design/standard/user/forgotpassword')}:
http://{$site_url}{concat("user/forgotpassword/", $hash_key, '/')|ezurl(no)}
{section-else}


{section show=$password}
{"New password"|i18n('design/standard/user/forgotpassword')}: {$password}
{/section}

{/section}

{/let}
