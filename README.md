# Teamspeak-3-Overview

# What should it do
It should show all rooms and users currently on a teamspeak server.

# How does it work

The two shell scripts are connecting to the teamspeak query and get all infos about channel and clients, then the auslesen.php read the files which are created from the scripts before and parse them and return html code which inherits a table which is responsible for showing all rooms and clients

But please notice that currently every request on the page will execute the getClients and getChannels script.

# How can i use it for myself
First you have to run a teamspeak server on a linux machine and know how to set the serveradmin password.

Then just download it, change the config.php variables to your teamspeak query account data and call the auslesenHTML() funtion via your own php files.

Also I use it on my own website so if you wanna see what it could look like visit https://wearethegamers.de/teamspeak3
If you find a bug or have a cool idea what i should add feel free to say it.

Or if you like something else from my website, the source code is also here on github https://github.com/SevenOperation/WebInterface
