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


-![alt text](http://i.imgur.com/WWLYo.gif "Frustrated cat can't believe this is the 12th time he's clicked on an auto-linked README.md URL")
