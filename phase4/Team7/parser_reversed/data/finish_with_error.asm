/ (fcn) sym.finish_with_error 3598
|           ; arg int arg_1_1      @ ebp+0x5
|           ; arg int arg_2        @ ebp+0x8
|           ; arg int arg_3        @ ebp+0xc
|           ; XREFS: CALL 0x080497e7  CALL 0x0804950c  CALL 0x0804929f  
|           ; XREFS: CALL 0x08049237  CALL 0x0804916d  CALL 0x08049105  
|           ; XREFS: CALL 0x08048f83  CALL 0x08048e48  
|           0x08048aa3      55             push ebp
|           0x08048aa4      89e5           mov ebp, esp
|           0x08048aa6      83ec18         sub esp, 0x18
|           0x08048aa9      c70424209904.  mov dword [esp], str.Transaction_failed. ; [0x8049920:4]=0x6e617254  LEA str.Transaction_failed. ; "Transaction failed." @ 0x8049920
|           0x08048ab0      e8cbfdffff     call sym.imp.puts
|           0x08048ab5      8b4508         mov eax, dword [ebp+arg_2]  ; [0x8:4]=0
|           0x08048ab8      890424         mov dword [esp], eax
|           0x08048abb      e8f0fdffff     call sym.imp.mysql_stmt_close
|           0x08048ac0      e8afffffff     call sym.close_free
|           0x08048ac5      c70424010000.  mov dword [esp], 1
|           0x08048acc      e8effdffff     call sym.imp.exit
