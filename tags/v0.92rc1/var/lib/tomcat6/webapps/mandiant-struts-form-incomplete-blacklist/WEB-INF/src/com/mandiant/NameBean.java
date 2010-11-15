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
		// the (?i) in the pattern below makes the match case insensitive
		final String blacklistpattern = "(?i).*<script>.*"; 
		if (name.matches(blacklistpattern)) 
		{ 				
			// invalid input 
			this.name = "Invalid Input";
		} else {
			this.name = name;
		}
    }

    private String name;

}
