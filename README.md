# Teamspeak-3-Overview

# What should it do
It should show all rooms and users currently on a teamspeak server.

# How does it work

The two shell scripts are connecting to the teamspeak query and get all infos about channel and clients, then the auslesen.php read the files which are created from the scripts before and parse them and return html code which inherits a table which is responsible for showing all rooms and clients

# How can i use it for myself
Just download it, then change the config.php variables to your teamspeak query account data and call the auslesenHTML() funtion via your own php files.
