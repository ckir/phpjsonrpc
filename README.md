phpjsonrpc
==========
Trivial programming tasks exposed as JSON-RPC methods.

Working Url
===========
You don't need to setup anything to see if you like it.
The SMD entry point is [here](http://phpjsonrpc.herokuapp.com/api/v1/server.php).
There is also a second SMD entry point related to Greek methods. The entry is [here](http://phpjsonrpc.herokuapp.com/api/v1/servergreek.php).

Documentation
=============
The documentation of the public exposed methods can be found [here](http://phpjsonrpc.herokuapp.com/api/v1/Docs/).
Additional examples for each method can be found in the api/v1/Examples folder.

Installation on Heroku
======================
1. Create a heroku application.

2. Change the build-pack: 
heroku config:set BUILDPACK_URL=https://github.com/iphoting/heroku-buildpack-php-tyler

3. Upload this repo to heroku and you are good to go.

Pull Requests
=============
Are very wellcome. Please contribute something that you don't want to rewrite again.
