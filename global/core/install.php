#!/bin/bash
# Usage 
# cd www/sites/dev/uos1-001-dev/
# /www/codebases/uos/live/global/core/newuniverse [universename]

PWD=${pwd}
UNIVERSENAME=$1
INSTALLSCRIPTPATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
UNIVERSECODEBASEPATH="$INSTALLSCRIPTPATH../../"
UNIVERSECODEBASEPATH="$(cd ${UNIVERSECODEBASEPATH%/*}; pwd)/${THING##*/}"

echo "Creating Universe"
echo "new node_universe($Universename)"
echo "Install path : $PWD"
echo $INSTALLSCRIPTPATH
echo "Codebase path : $UNIVERSECODEBASEPATH"

#$codebase=/www/codebases/uos/live/global
#$universename=julian


#mkdir universes/
#mkdir universes/$universename/
#mkdir universes/$universename/data
#ln -s  /www/sites/dev/uos1-001-dev/universes/$universename/global
