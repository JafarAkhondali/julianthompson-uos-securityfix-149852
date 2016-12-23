<<<<<<< HEAD
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
=======
# Chat API [![Latest Stable Version](https://poser.pugx.org/whatsapp/chat-api/v/stable)](https://packagist.org/packages/whatsapp/chat-api) [![Total Downloads](https://poser.pugx.org/whatsapp/chat-api/downloads)](https://packagist.org/packages/whatsapp/chat-api) [![License](https://poser.pugx.org/whatsapp/chat-api/license)](https://packagist.org/packages/whatsapp/chat-api)

Interface to WhatsApp Messenger

**Read the [wiki](https://github.com/mgp25/Chat-API/wiki)** and previous issues before opening a new one! Maybe your issue is already answered.

For new WhatsApp updates check **[WhatsApp incoming updates log](https://github.com/mgp25/Chat-API/wiki/WhatsApp-incoming-updates)**

**Do you like this project? Support it by donating**
- ![Paypal](https://raw.githubusercontent.com/reek/anti-adblock-killer/gh-pages/images/paypal.png) Paypal: [Donate](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YNVNPLE45DNG6)
- ![btc](https://camo.githubusercontent.com/4bc31b03fc4026aa2f14e09c25c09b81e06d5e71/687474703a2f2f7777772e6d6f6e747265616c626974636f696e2e636f6d2f696d672f66617669636f6e2e69636f) Bitcoin: 1DCEpC9wYXeUGXS58qSsqKzyy7HLTTXNYe 

----------
### Installation

```sh
composer require whatsapp/chat-api
```

- **Requires:** [PHP Protobuf](https://github.com/allegro/php-protobuf) and [Curve25519](https://github.com/mgp25/curve25519-php) to enable end to end encryption

### Special thanks

- [CODeRUS](https://github.com/CODeRUS)
- [tgalal](https://github.com/tgalal)
- [SikiFn](https://github.com/SikiFn)
- [0xTryCatch](https://github.com/0xTryCatch)
- [Shirioko](https://github.com/shirioko)
- [sinjuice](https://github.com/sinjuice)

Also Ahmed Moh'd ([fb.com/ahmed.mhd](fb.com/ahmed.mhd)) and Ali Hubail ([@hubail](https://twitter.com/hubail)) for making this project possible.

And everyone that contributes to it.

----------

### What is WhatsApp?
According to [the company](http://www.whatsapp.com/):

> “WhatsApp Messenger is a cross-platform mobile messenger that replaces SMS and works through the existing internet data plan of your device. WhatsApp is available for iPhone, BlackBerry, Android, Windows Phone, Nokia Symbian60 & S40 phones. Because WhatsApp Messenger uses the same internet data plan that you use for email and web browsing, there is no cost to message and stay in touch with your friends.”

Jan. 2015: 30 billion messages per day, ~700 million users.

# License

As of November 1, 2015 Chat API is licensed under the GPLv3+: http://www.gnu.org/licenses/gpl-3.0.html.

# Terms and conditions

- You will NOT use this API for marketing purposes (spam, massive sending...).
- We do NOT give support to anyone that wants this API to send massive messages or similar.
- We reserve the right to block any user of this repository that does not meet these conditions.

## Legal

This code is in no way affiliated with, authorized, maintained, sponsored or endorsed by WhatsApp or any of its affiliates or subsidiaries. This is an independent and unofficial API. Use at your own risk.


##Cryptography Notice

This distribution includes cryptographic software. The country in which you currently reside may have restrictions on the import, possession, use, and/or re-export to another country, of encryption software. BEFORE using any encryption software, please check your country's laws, regulations and policies concerning the import, possession, or use, and re-export of encryption software, to see if this is permitted. See http://www.wassenaar.org/ for more information.

The U.S. Government Department of Commerce, Bureau of Industry and Security (BIS), has classified this software as Export Commodity Control Number (ECCN) 5D002.C.1, which includes information security software using or performing cryptographic functions with asymmetric algorithms. The form and manner of this distribution makes it eligible for export under the License Exception ENC Technology Software Unrestricted (TSU) exception (see the BIS Export Administration Regulations, Section 740.13) for both object code and source code.
>>>>>>> 5004d19875ae7f9814421daaa40a5d9b82e2d8d8
