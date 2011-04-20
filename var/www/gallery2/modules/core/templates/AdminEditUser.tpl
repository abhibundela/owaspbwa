{*
 * $Revision: 1.26 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Edit a user"} </h2>
</div>

<div class="gbBlock">
  <h4>
    {g->text text="Username"}
    <span class="giSubtitle"> {g->text text="(required)"} </span>
  </h4>

  <input type="hidden" name="{g->formVar var="userId"}" value="{$AdminEditUser.user.id}"/>
  <input type="text" id="giFormUsername" size="30"
   name="{g->formVar var="form[userName]"}" value="{$form.userName}"/>

  {if isset($form.error.userName.duplicate)}
  <div class="giError">
    {g->text text="That username is already in use"}
  </div>
  {/if}
  {if isset($form.error.userName.missing)}
  <div class="giError">
    {g->text text="You must enter a new username"}
  </div>
  {/if}

  <h4> {g->text text="Full Name"} </h4>

  <input type="text" size="32"
   name="{g->formVar var="form[fullName]"}" value="{$form.fullName}"/>

  {if $AdminEditUser.show.email}
    <h4>
      {g->text text="E-mail Address"}
      <span class="giSubtitle"> {g->text text="(suggested)"} </span>
    </h4>

    <input type="text" size="32" name="{g->formVar var="form[email]"}" value="{$form.email}"/>

    {if isset($form.error.email.missing)}
    <div class="giError">
      {g->text text="You must enter an email address"}
    </div>
    {/if}
    {if isset($form.error.email.invalid)}
    <div class="giError">
      {g->text text="Invalid email address"}
    </div>
    {/if}
  {/if}

  {if $AdminEditUser.show.language}
    <h4> {g->text text="Language"} </h4>

    <select name="{g->formVar var="form[language]"}">
      {html_options options=$AdminEditUser.languageList selected=$form.language}
    </select>
  {/if}

  {if $AdminEditUser.show.password}
    <h4>
      {g->text text="Password"}
      <span class="giSubtitle"> {g->text text="(required)"} </span>
    </h4>

    <input type="password" size="32" name="{g->formVar var="form[password1]"}"/>

    {if isset($form.error.password1.missing)}
    <div class="giError">
      {g->text text="You must enter a password"}
    </div>
    {/if}

    <h4>
      {g->text text="Verify Password"}
      <span class="giSubtitle"> {g->text text="(required)"} </span>
    </h4>

    <input type="password" size="32" name="{g->formVar var="form[password2]"}"/>

    {if isset($form.error.password2.missing)}
    <div class="giError">
      {g->text text="You must enter the password a second time"}
    </div>
    {/if}
    {if isset($form.error.password2.mismatch)}
    <div class="giError">
      {g->text text="The passwords you entered did not match"}
    </div>
    {/if}
  {/if}
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][save]"}" value="{g->text text="Save"}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][undo]"}" value="{g->text text="Reset"}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][cancel]"}" value="{g->text text="Cancel"}"/>
</div>
