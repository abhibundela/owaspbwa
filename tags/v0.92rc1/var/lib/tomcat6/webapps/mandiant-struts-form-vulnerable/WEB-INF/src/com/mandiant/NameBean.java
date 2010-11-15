// Adapted from http://wiki.apache.org/struts/StrutsMinimalInstall

package com.mandiant;

import java.io.Serializable;

import org.apache.struts.action.ActionForm;

public class NameBean
    extends ActionForm
    implements Serializable
{

    public NameBean ()
    {
    }

    public String getName ()
    {
        return name;
    }

    public void setName (String name)
    {
        this.name = name;
    }

    private String name;

}
