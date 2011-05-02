{*
 * $Revision: 1.7 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
Windows Registry Editor Version 5.00

[HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Explorer\PublishingWizard\PublishingWizard\Providers\{$DownloadRegistryFile.domain}]
"displayname"="{g->text text="%s at %s" arg1=$DownloadRegistryFile.title arg2=$DownloadRegistryFile.domain}"
"description"="{g->text text="Publish Your Photos and Movies to %s" arg1=$DownloadRegistryFile.title}"
"href"="{g->url arg1="view=publishxp.Login" forceFullUrl=1 htmlEntities=0}"
"icon"="{g->url href="favicon.ico" forceFullUrl=1 htmlEntities=0}"
