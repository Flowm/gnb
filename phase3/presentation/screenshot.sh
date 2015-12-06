mkdir -p screenshots            # Creates it if missing

echo "Now open up your presentation and set it to fullscreen."
echo "You have 10 seconds."
t=10
while [ $t -gt 0 ]; do
      echo "$t..."
      sleep 1
      t=$(($t-1))
done

i=1000000
pause=1
while true; do
    scrot -d $pause "screenshots/shot$i.png"
    xdotool key space
    i=$(($i+1))
done
