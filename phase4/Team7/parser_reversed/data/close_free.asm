/ (fcn) sym.close_free 47
|           ; CALL XREF from 0x08048d0f (sym.close_free)
|           ; CALL XREF from 0x08049813 (sym.close_free)
|           ; CALL XREF from 0x08048d89 (sym.close_free)
|           ; CALL XREF from 0x08048ac0 (sym.close_free)
|           0x08048a74      55             push ebp
|           0x08048a75      89e5           mov ebp, esp
|           0x08048a77      83ec18         sub esp, 0x18
|           0x08048a7a      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|           0x08048a7f      890424         mov dword [esp], eax
|           0x08048a82      e869fdffff     call sym.imp.free
|           0x08048a87      a188b00408     mov eax, dword [obj.inpfile] ; [0x804b088:4]=0x75746e75  LEA obj.inpfile ; "untu/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b088
|           0x08048a8c      890424         mov dword [esp], eax
|           0x08048a8f      e85cfdffff     call sym.imp.free
|           0x08048a94      a18cb00408     mov eax, dword [obj.fid]    ; [0x804b08c:4]=0x6e694c2f  LEA obj.fid ; "/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b08c
|           0x08048a99      890424         mov dword [esp], eax
|           0x08048a9c      e85ffdffff     call sym.imp.fclose
|           0x08048aa1      c9             leave
\           0x08048aa2      c3             ret
