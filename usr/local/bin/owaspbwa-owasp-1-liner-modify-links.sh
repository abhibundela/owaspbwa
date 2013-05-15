#/bin/bash
cd /owaspbwa/owasp-1-liner-git

# Absolute links to point to http (port 80) and to the /oneliner path
find . -type f -print0 | xargs -0 sed -i 's^https\?://local\.1-liner\.org:[0-9]\+^http://local.1-liner.org/oneliner^g'
find . -type f -print0 | xargs -0 sed -i 's^https\?://attackr\.se:[0-9]\+^http://attackr.se/oneliner^g'
find . -type f -print0 | xargs -0 sed -i 's^https\?://other\.1-liner\.org:[0-9]\+^http://other.1-liner.org/oneliner^g'
find . -type f -print0 | xargs -0 sed -i 's^https\?://3rdparty\.net:[0-9]\+^http://3rd-party.info/oneliner^g'
find . -type f -print0 | xargs -0 sed -i 's^https\?://local\.l-liner\.org:[0-9]\+^http://local.l-liner.org/oneliner^g'

# Replace any remaining references to 3rdparty.net with references to 3rd-party.info
find . -type f -print0 | xargs -0 sed -i 's^3rdparty\.net^3rd-party.info^g'

# Fix base hrefs on some .jsp files
find src/main/webapp/securish -type f -print0 | xargs -0 sed -i 's^base href="http://local.1-liner.org/oneliner/"^base href="http://local.1-liner.org/oneliner/securish/"^g'
find src/main/webapp/vulnerable -type f -print0 | xargs -0 sed -i 's^base href="http://local.1-liner.org/oneliner/"^base href="http://local.1-liner.org/oneliner/vulnerable/"^g'
find src/main/webapp/integration -type f -print0 | xargs -0 sed -i 's^base href="http://local.1-liner.org/oneliner/"^base href="http://local.1-liner.org/oneliner/integration/"^g'

# Fix relative links to javascript and web services
find . -type f -print0 | xargs -0 sed -i "s^'ws/^'../ws/^g"
find . -type f -print0 | xargs -0 sed -i 's^\"js/^\"../js/^g'
find . -type f -print0 | xargs -0 sed -i 's^\"/ws/^\"../ws/^g'

cd - # return to previous directory
