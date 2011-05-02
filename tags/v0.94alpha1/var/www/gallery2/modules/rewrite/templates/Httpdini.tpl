{*
 * $Revision: 1.3 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
# BEGIN Gallery 2 Url Rewrite section (GalleryID: {$Httpdini.galleryId})
# Do not edit this section manually. Gallery will overwrite it automatically.

RewriteCond Host: {$Httpdini.host}
RewriteRule {$Httpdini.galleryDirectory}modules/rewrite/data/isapi_rewrite/Rewrite.txt {$Httpdini.galleryDirectory}modules/rewrite/data/isapi_rewrite/Works.txt [O]

{foreach from=$Httpdini.rules item=rule}
{if isset($rule.settings.restrict)}
  {foreach from=$rule.settings.restrict item=condition}
    RewriteCond URL .*\?.*{$condition}.*
  {/foreach}

  {foreach from=$rule.settings.exempt item=host}
    RewriteCond Referer: (?!.*://{$host}/.*)
  {/foreach}

  {if $Httpdini.allowEmptyReferer && !empty($rule.settings.exempt)}
    RewriteCond Referer: (?!^$)
  {/if}

  RewriteCond Host: {$Httpdini.host}
  RewriteRule {$Httpdini.rewriteBase}.* {$Httpdini.rewriteBase}{$Httpdini.baseFile}{$rule.queryString}   [{$rule.settings.flags}]
{else}
RewriteCond Host: {$Httpdini.host}
{if strpos($rule.queryString, 'view=core.DownloadItem') !== false}
  RewriteRule {$Httpdini.rewriteBase}{$rule.pattern} {$Httpdini.galleryDirectory}{$Httpdini.mainPhp}?{$rule.queryString}   [{$rule.settings.flags}]
{else}
  RewriteRule {$Httpdini.rewriteBase}{$rule.pattern} {$Httpdini.rewriteBase}{$Httpdini.baseFile}{$rule.queryString}   [{$rule.settings.flags}]
{/if}
RewriteCond Host: {$Httpdini.host}
{if strpos($rule.queryString, 'view=core.DownloadItem') !== false}
  RewriteRule {$Httpdini.rewriteBase}{$rule.pattern}?(.*) {$Httpdini.galleryDirectory}{$Httpdini.mainPhp}?{$rule.queryString}&${$rule.queryStringId}   [{$rule.settings.flags}]
{else}
  RewriteRule {$Httpdini.rewriteBase}{$rule.pattern}?(.*) {$Httpdini.rewriteBase}{$Httpdini.baseFile}{$rule.queryString}&${$rule.queryStringId}   [{$rule.settings.flags}]
{/if}
{/if}

{/foreach}

# END Url Rewrite section
