/**
 * Copyright (C) 2001 Yasna.com. All rights reserved.
 *
 * ===================================================================
 * The Apache Software License, Version 1.1
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *
 * 3. The end-user documentation included with the redistribution,
 *    if any, must include the following acknowledgment:
 *       "This product includes software developed by
 *        Yasna.com (http://www.yasna.com)."
 *    Alternately, this acknowledgment may appear in the software itself,
 *    if and wherever such third-party acknowledgments normally appear.
 *
 * 4. The names "Yazd" and "Yasna.com" must not be used to
 *    endorse or promote products derived from this software without
 *    prior written permission. For written permission, please
 *    contact yazd@yasna.com.
 *
 * 5. Products derived from this software may not be called "Yazd",
 *    nor may "Yazd" appear in their name, without prior written
 *    permission of Yasna.com.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED.  IN NO EVENT SHALL YASNA.COM OR
 * ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 * USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 * ====================================================================
 *
 * This software consists of voluntary contributions made by many
 * individuals on behalf of Yasna.com. For more information
 * on Yasna.com, please see <http://www.yasna.com>.
 */

/**
 * Copyright (C) 2000 CoolServlets.com. All rights reserved.
 *
 * ===================================================================
 * The Apache Software License, Version 1.1
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *
 * 3. The end-user documentation included with the redistribution,
 *    if any, must include the following acknowledgment:
 *       "This product includes software developed by
 *        CoolServlets.com (http://www.coolservlets.com)."
 *    Alternately, this acknowledgment may appear in the software itself,
 *    if and wherever such third-party acknowledgments normally appear.
 *
 * 4. The names "Jive" and "CoolServlets.com" must not be used to
 *    endorse or promote products derived from this software without
 *    prior written permission. For written permission, please
 *    contact webmaster@coolservlets.com.
 *
 * 5. Products derived from this software may not be called "Jive",
 *    nor may "Jive" appear in their name, without prior written
 *    permission of CoolServlets.com.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED.  IN NO EVENT SHALL COOLSERVLETS.COM OR
 * ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 * USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 * ====================================================================
 *
 * This software consists of voluntary contributions made by many
 * individuals on behalf of CoolServlets.com. For more information
 * on CoolServlets.com, please see <http://www.coolservlets.com>.
 */

package com.Yasna.forum.database;

import java.sql.Connection;
import java.sql.SQLException;
import javax.sql.DataSource;
import java.util.Properties;
import java.util.Enumeration;
import javax.naming.Context;
import javax.naming.InitialContext;
import javax.naming.NamingException;
import com.Yasna.forum.PropertyManager;

/**
 * An implementation of DbConnectionProvider that utilizes a JDBC 2.0 DataSource
 * made available via JNDI. This is useful for application servers where a pooled
 * data connection is already provided so Jive can share the pool with the
 * other applications.<p>
 *
 * The JNDI location of the DataSource is retrieved from the
 * {@link com.Yasna.forum.PropertyManager} as the
 * <code>JNDIDataSource.name</code> property. This can be overiden by setting
 * the provider's <code>name</code> property if required.
 *
 * @author <a href="mailto:joe@truemesh.com">Joe Walnes</a>
 *
 * @see com.Yasna.forum.database.DbConnectionProvider
 */
public class DataSourceConnectionProvider extends DbConnectionProvider {

    private static final String NAME        = "JNDI DataSource Connection Provider";
    private static final String DESCRIPTION =
        "Connection Provider for Jive to lookup pooled "
        + "DataSource from JNDI location. Requires 'name' "
        + "property with JNDI location. This can be set in "
        + "the properties file as 'JNDIDataSource.name'";
    private static final String AUTHOR      = "Joe Walnes - joe@truemesh.com";
    private static final int MAJOR_VERSION  = 1;
    private static final int MINOR_VERSION  = 0;
    private static final boolean POOLED     = true;

    private Properties properties;
    private DataSource dataSource;

    private static final boolean DEBUG = false;

    /**
     * Keys of JNDI properties to query PropertyManager for.
     */
    private static final String[] jndiPropertyKeys = {
        Context.APPLET ,
        Context.AUTHORITATIVE ,
        Context.BATCHSIZE ,
        Context.DNS_URL ,
        Context.INITIAL_CONTEXT_FACTORY ,
        Context.LANGUAGE ,
        Context.OBJECT_FACTORIES ,
        Context.PROVIDER_URL ,
        Context.REFERRAL ,
        Context.SECURITY_AUTHENTICATION ,
        Context.SECURITY_CREDENTIALS ,
        Context.SECURITY_PRINCIPAL ,
        Context.SECURITY_PROTOCOL ,
        Context.STATE_FACTORIES ,
        Context.URL_PKG_PREFIXES
    };

    /**
     * Initialize.
     */
    public DataSourceConnectionProvider() {
        debug("constructor()");
        properties = new Properties();
        setProperty("name",PropertyManager.getProperty("JNDIDataSource.name"));
    }

    /**
     * Lookup DataSource from JNDI context.
    */
    protected void start() {
        debug("start()");
        String name = getProperty("name");
        if (name==null || name.length()==0) {
            error("No name specified for DataSource JNDI lookup - 'name' " +
            "Property should be set.", null);
            return;
        }
        try {
            Properties contextProperties = new Properties();
            for (int i=0; i<jndiPropertyKeys.length; i++) {
                String k = jndiPropertyKeys[i];
                String v = PropertyManager.getProperty(k);
                if (v != null) {
                    contextProperties.setProperty(k,v);
                }
            }
            Context context = new InitialContext(contextProperties);
            dataSource = (DataSource) context.lookup( name );
        }
        catch (Exception e) {
            error("Could not lookup DataSource at '" + name + "'",e);
        }
    }

    /**
     * Destroy then start.
     */
    protected void restart() {
        debug("restart()");
        destroy();
        start();
    }

    /**
     * Save properties.
     */
    protected void destroy() {
        debug("destroy()");
        String name = getProperty("name");
        if (name!=null && name.length()>0) {
            PropertyManager.setProperty("JNDIDataSource.name", name);
        }
    }

    /**
     * Get new Connection from DataSource.
     */
    public Connection getConnection() {
        debug("getConnection()");
        if (dataSource==null) {
            error("DataSource has not yet been looked up",null);
            return null;
        }
        try {
            return dataSource.getConnection();
        }
        catch (SQLException e) {
            error("Could not retrieve Connection from DataSource",e);
            return null;
        }
    }

    public String getProperty(String name) {
        debug("getProperty('"+name+"+')");
        return properties.getProperty(name);
    }

    public void setProperty(String name, String value) {
        debug("setProperty('"+name+"+','"+value+"')");
        properties.setProperty(name,value);
    }

    public Enumeration propertyNames() {
        debug("propertyNames()");
        return properties.propertyNames();
    }

    public String getPropertyDescription(String name) {
        debug("getPropertyDescription('"+name+"')");
        if (name.equals("name")) {
            return "JNDI name to lookup. eg: java:comp/env/jdbc/MyDataSource";
        }
        else {
            return null;
        }
    }

   /**
    * Log an error.
    *
    * @param msg Description of error
    * @param e Exception to printStackTrace (may be null)
    */
    private final void error(String msg, Exception e) {
        System.err.println("Error: "+msg);
        if (e!=null) {
            e.printStackTrace();
        }
    }

    /**
     * Display messages for debugging
     */
    private final void debug(String msg) {
        if (DEBUG) {
            System.err.println("DEBUG: "+msg);
        }
    }
}
