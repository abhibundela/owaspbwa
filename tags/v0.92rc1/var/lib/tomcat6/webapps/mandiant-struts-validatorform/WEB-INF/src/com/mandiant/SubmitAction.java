// Adapted from http://wiki.apache.org/struts/StrutsMinimalInstall

package com.mandiant;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.struts.action.Action;
import org.apache.struts.action.ActionForm;
import org.apache.struts.action.ActionForward;
import org.apache.struts.action.ActionMapping;

public class SubmitAction
    extends Action
{

    public ActionForward execute (ActionMapping mapping, ActionForm form,
            HttpServletRequest request, HttpServletResponse response)
        throws Exception
    {
        NameBean nameBean = (NameBean) form;
        // here you could call NameBean methods to alter the object, as below 
        // nameBean.setName(nameBean.getName() + " esquire");
        return mapping.findForward("success");
    }
}
