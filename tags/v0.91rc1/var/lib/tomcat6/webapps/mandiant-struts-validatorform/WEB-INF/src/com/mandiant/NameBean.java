// Adapted from http://wiki.apache.org/struts/StrutsMinimalInstall

package com.mandiant;

import java.io.Serializable;

import org.apache.struts.validator.ValidatorForm;

public class NameBean
    extends ValidatorForm
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
