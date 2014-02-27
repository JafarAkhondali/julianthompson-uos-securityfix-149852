# UOS - Universe OS

Universe Operating System

## Install
There are a few different ways you can install UOS :

* Download the zipfile from the [releases](https://github.com/julianthompson/uos/releases) page and install it. 
* Checkout the source: `git clone git://github.com/julianthompson/uos.git` and install it yourself.
   
## Setup

Command line:
```
mkdir codebases
cd codebases
mkdir uos
cd uos
git clone git://github.com/julianthompson/uos.git v0.1
ln -s v0.1 live
```

At the end of .bash_profile, add the following:
```
export PATH=$PATH:/codesbases/uos/live/global/bin/uos-install
```

Now move to where your universe should be located (as per virtual host file) :
```
cd /www/sites/dev/myuniverses/
uos-install [universename]
```

Create a virtualhost file :
```
cd /www/vhosts/
vi universename.conf
```

```
<VirtualHost *:80>

	ServerName      uni1-001.local
	
	ServerAlias     universeos *.universeos universeos.local universeos.localhost
	
	ServerAdmin     admin@universeos.localhost
	
	DocumentRoot    /www/sites/yourpath/www
	
	ErrorDocument   404  /global/uos.php
	ErrorDocument   403  /global/uos.php
	
	ErrorLog        /www/sites/yourpath/log/error.log
	CustomLog       /www/sites/yourpath/log/access.log combined
	
	DirectoryIndex  index.php index.html /global/uos.php
	
	SetEnv          UOS_DATABASE    mysql://username:password@localhost/universe
	
	<Directory /www/sites/yourpath/www >
	        Options +FollowSymLinks
	        AllowOverride All
	        Order allow,deny
	        Allow from all
	</Directory>
	
	<Directory /www/sites/yourpath/www/data/private >
	        Order allow,deny
	        Deny from all
	</Directory>
	
	#php_value error_reporting E_ALL & ~E_NOTICE & ~E_DEPRECATED
	php_flag display_errors On
	php_value date.timezone Europe/London

</VirtualHost>
```


-![alt text](http://i.imgur.com/WWLYo.gif "Frustrated cat can't believe this is the 12th time he's clicked on an auto-linked README.md URL")
