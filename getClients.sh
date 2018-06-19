{
sleep 0.1
echo "login $1 $2"
sleep 0.1
echo "use 1"
sleep 0.1
echo "clientlist"
sleep 0.1
echo "quit"
} |telnet localhost 10011 > clients
