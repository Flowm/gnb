            ;-- main:
/ (fcn) sym.main 3455
|           ; arg int arg_1_1      @ ebp+0x5
|           ; arg int arg_3        @ ebp+0xc
|           ; DATA XREF from 0x080489d7 (sym.main)
|           0x08048ad1      55             push ebp
|           0x08048ad2      89e5           mov ebp, esp
|           0x08048ad4      57             push edi
|           0x08048ad5      56             push esi
|           0x08048ad6      53             push ebx
|           0x08048ad7      83e4f0         and esp, 0xfffffff0
|           0x08048ada      81ecd0050000   sub esp, 0x5d0
|           0x08048ae0      8b450c         mov eax, dword [ebp+arg_3]  ; [0xc:4]=0
|           0x08048ae3      8944242c       mov dword [esp + 0x2c], eax
|           0x08048ae7      65a114000000   mov eax, dword gs:[0x14]    ; [0x14:4]=1
|           0x08048aed      898424cc0500.  mov dword [esp + 0x5cc], eax
|           0x08048af4      31c0           xor eax, eax
|           0x08048af6      c78424dc0200.  mov dword [esp + 0x2dc], 0
|           0x08048b01      c78424e00200.  mov dword [esp + 0x2e0], 0
|           0x08048b0c      c70424000000.  mov dword [esp], 0
|           0x08048b13      e868feffff     call sym.imp.mysql_init
|           0x08048b18      898424e40200.  mov dword [esp + 0x2e4], eax
|           0x08048b1f      8b8424e40200.  mov eax, dword [esp + 0x2e4] ; [0x2e4:4]=18
|           0x08048b26      890424         mov dword [esp], eax
|           0x08048b29      e802fdffff     call sym.imp.mysql_stmt_init
|           0x08048b2e      898424e80200.  mov dword [esp + 0x2e8], eax
|           0x08048b35      c78424ec0200.  mov dword [esp + 0x2ec], str.localhost ; [0x8049934:4]=0x61636f6c  LEA str.localhost ; "localhost" @ 0x8049934
|           0x08048b40      c78424f00200.  mov dword [esp + 0x2f0], str.root ; [0x804993e:4]=0x746f6f72  LEA str.root ; "root" @ 0x804993e
|           0x08048b4b      c78424f40200.  mov dword [esp + 0x2f4], str.crazypassword ; [0x8049943:4]=0x7a617263  LEA str.crazypassword ; "crazypassword" @ 0x8049943
|           0x08048b56      c78424f80200.  mov dword [esp + 0x2f8], str.Banking ; [0x8049951:4]=0x6b6e6142  LEA str.Banking ; "Banking" @ 0x8049951
|           0x08048b61      c78424b80500.  mov dword [esp + 0x5b8], 0x52505041 ; [0x52505041:4]=-1
|           0x08048b6c      c78424bc0500.  mov dword [esp + 0x5bc], 0x4445564f ; [0x4445564f:4]=-1
|           0x08048b77      c78424c00500.  mov dword [esp + 0x5c0], 0
|           0x08048b82      c78424c40500.  mov dword [esp + 0x5c4], 0
|           0x08048b8d      c78424c80500.  mov dword [esp + 0x5c8], 0
|           0x08048b98      c70424c80000.  mov dword [esp], 0xc8       ; [0xc8:4]=208
|           0x08048b9f      e8ccfcffff     call sym.imp.malloc
|           0x08048ba4      a390b00408     mov dword [obj.sndaccount], eax
|           0x08048ba9      c70424c80000.  mov dword [esp], 0xc8       ; [0xc8:4]=208
|           0x08048bb0      e8bbfcffff     call sym.imp.malloc
|           0x08048bb5      a388b00408     mov dword [obj.inpfile], eax
|           0x08048bba      c70424c80000.  mov dword [esp], 0xc8       ; [0xc8:4]=208
|           0x08048bc1      e8aafcffff     call sym.imp.malloc
|           0x08048bc6      a394b00408     mov dword [obj.transactiontan], eax
|           0x08048bcb      837d0805       cmp dword [ebp + 8], 5      ; [0x5:4]=257
|       ,=< 0x08048bcf      0f85fb000000   jne 0x8048cd0              
|       |   0x08048bd5      8b44242c       mov eax, dword [esp + 0x2c] ; [0x2c:4]=0x280009  ; ','
|       |   0x08048bd9      83c004         add eax, 4
|       |   0x08048bdc      8b00           mov eax, dword [eax]
|       |   0x08048bde      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|       |   0x08048be6      89c2           mov edx, eax
|       |   0x08048be8      b800000000     mov eax, 0
|       |   0x08048bed      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|       |   0x08048bf1      89d7           mov edi, edx
|       |   0x08048bf3      f2ae           repne scasb al, byte es:[edi]
|       |   0x08048bf5      89c8           mov eax, ecx
|       |   0x08048bf7      f7d0           not eax
|       |   0x08048bf9      8d48ff         lea ecx, [eax - 1]
|       |   0x08048bfc      8b44242c       mov eax, dword [esp + 0x2c] ; [0x2c:4]=0x280009  ; ','
|       |   0x08048c00      83c004         add eax, 4
|       |   0x08048c03      8b00           mov eax, dword [eax]
|       |   0x08048c05      89c2           mov edx, eax
|       |   0x08048c07      a188b00408     mov eax, dword [obj.inpfile] ; [0x804b088:4]=0x75746e75  LEA obj.inpfile ; "untu/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b088
|       |   0x08048c0c      894c2408       mov dword [esp + 8], ecx
|       |   0x08048c10      89542404       mov dword [esp + 4], edx
|       |   0x08048c14      890424         mov dword [esp], eax
|       |   0x08048c17      e834fdffff     call sym.imp.strncpy
|       |   0x08048c1c      8b44242c       mov eax, dword [esp + 0x2c] ; [0x2c:4]=0x280009  ; ','
|       |   0x08048c20      83c008         add eax, 8
|       |   0x08048c23      8b00           mov eax, dword [eax]
|       |   0x08048c25      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|       |   0x08048c2d      89c2           mov edx, eax
|       |   0x08048c2f      b800000000     mov eax, 0
|       |   0x08048c34      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|       |   0x08048c38      89d7           mov edi, edx
|       |   0x08048c3a      f2ae           repne scasb al, byte es:[edi]
|       |   0x08048c3c      89c8           mov eax, ecx
|       |   0x08048c3e      f7d0           not eax
|       |   0x08048c40      8d48ff         lea ecx, [eax - 1]
|       |   0x08048c43      8b44242c       mov eax, dword [esp + 0x2c] ; [0x2c:4]=0x280009  ; ','
|       |   0x08048c47      83c008         add eax, 8
|       |   0x08048c4a      8b00           mov eax, dword [eax]
|       |   0x08048c4c      89c2           mov edx, eax
|       |   0x08048c4e      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|       |   0x08048c53      894c2408       mov dword [esp + 8], ecx
|       |   0x08048c57      89542404       mov dword [esp + 4], edx
|       |   0x08048c5b      890424         mov dword [esp], eax
|       |   0x08048c5e      e8edfcffff     call sym.imp.strncpy
|       |   0x08048c63      8b44242c       mov eax, dword [esp + 0x2c] ; [0x2c:4]=0x280009  ; ','
|       |   0x08048c67      83c00c         add eax, 0xc
|       |   0x08048c6a      8b00           mov eax, dword [eax]
|       |   0x08048c6c      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|       |   0x08048c74      89c2           mov edx, eax
|       |   0x08048c76      b800000000     mov eax, 0
|       |   0x08048c7b      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|       |   0x08048c7f      89d7           mov edi, edx
|       |   0x08048c81      f2ae           repne scasb al, byte es:[edi]
|       |   0x08048c83      89c8           mov eax, ecx
|       |   0x08048c85      f7d0           not eax
|       |   0x08048c87      8d48ff         lea ecx, [eax - 1]
|       |   0x08048c8a      8b44242c       mov eax, dword [esp + 0x2c] ; [0x2c:4]=0x280009  ; ','
|       |   0x08048c8e      83c00c         add eax, 0xc
|       |   0x08048c91      8b00           mov eax, dword [eax]
|       |   0x08048c93      89c2           mov edx, eax
|       |   0x08048c95      a194b00408     mov eax, dword [obj.transactiontan] ; [0x804b094:4]=0x2e362e34  LEA obj.transactiontan ; "4.6.3-1ubuntu5) 4.6.3" @ 0x804b094
|       |   0x08048c9a      894c2408       mov dword [esp + 8], ecx
|       |   0x08048c9e      89542404       mov dword [esp + 4], edx
|       |   0x08048ca2      890424         mov dword [esp], eax
|       |   0x08048ca5      e8a6fcffff     call sym.imp.strncpy
|       |   0x08048caa      ba59990408     mov edx, 0x8049959
|       |   0x08048caf      a188b00408     mov eax, dword [obj.inpfile] ; [0x804b088:4]=0x75746e75  LEA obj.inpfile ; "untu/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b088
|       |   0x08048cb4      89542404       mov dword [esp + 4], edx
|       |   0x08048cb8      890424         mov dword [esp], eax
|       |   0x08048cbb      e890fbffff     call sym.imp.fopen64
|       |   0x08048cc0      a38cb00408     mov dword [obj.fid], eax
|       |   0x08048cc5      a18cb00408     mov eax, dword [obj.fid]    ; [0x804b08c:4]=0x6e694c2f  LEA obj.fid ; "/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b08c
|       |   0x08048cca      85c0           test eax, eax
|      ,==< 0x08048ccc      7435           je 0x8048d03               
|     ,===< 0x08048cce      eb50           jmp 0x8048d20              
|     |||   ; JMP XREF from 0x08048bcf (sym.main)
|     ||`-> 0x08048cd0      b85c990408     mov eax, str.Not_enough_arguments_for_the_program ; "Not enough arguments for the program" @ 0x804995c
|     ||    0x08048cd5      890424         mov dword [esp], eax
|     ||    0x08048cd8      e803fbffff     call sym.imp.printf
|     ||    0x08048cdd      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|     ||    0x08048ce2      890424         mov dword [esp], eax
|     ||    0x08048ce5      e806fbffff     call sym.imp.free
|     ||    0x08048cea      a188b00408     mov eax, dword [obj.inpfile] ; [0x804b088:4]=0x75746e75  LEA obj.inpfile ; "untu/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b088
|     ||    0x08048cef      890424         mov dword [esp], eax
|     ||    0x08048cf2      e8f9faffff     call sym.imp.free
|     ||    0x08048cf7      c70424010000.  mov dword [esp], 1
|     ||    0x08048cfe      e8bdfbffff     call sym.imp.exit
|     ||    ; JMP XREF from 0x08048ccc (sym.main)
|     |`--> 0x08048d03      c70424819904.  mov dword [esp], str.File_does_not_exist ; [0x8049981:4]=0x656c6946  LEA str.File_does_not_exist ; "File does not exist" @ 0x8049981
|     |     0x08048d0a      e871fbffff     call sym.imp.puts
|     |     0x08048d0f      e860fdffff     call sym.close_free
|     |     0x08048d14      c70424010000.  mov dword [esp], 1
|     |     0x08048d1b      e8a0fbffff     call sym.imp.exit
|     |     ; JMP XREF from 0x08048cce (sym.main)
|     `---> 0x08048d20      c78424d80200.  mov dword [esp + 0x2d8], 0
|       ,=< 0x08048d2b      eb7f           jmp 0x8048dac              
|       |   ; JMP XREF from 0x08048dc8 (sym.main)
|      .--> 0x08048d2d      80bc24030300.  cmp byte [esp + 0x303], 0x2f ; [0x2f:1]=0 ; '/'
|     ,===< 0x08048d35      7e0a           jle 0x8048d41              
|     |||   0x08048d37      80bc24030300.  cmp byte [esp + 0x303], 0x39 ; [0x39:1]=0 ; '9'
|    ,====< 0x08048d3f      7e59           jle 0x8048d9a              
|    ||||   ; JMP XREF from 0x08048d35 (sym.main)
|    |`---> 0x08048d41      80bc24030300.  cmp byte [esp + 0x303], 0x20 ; [0x20:1]=168
|    |,===< 0x08048d49      744f           je 0x8048d9a               
|    ||||   0x08048d4b      80bc24030300.  cmp byte [esp + 0x303], 0x2e ; [0x2e:1]=40 ; '.'
|   ,=====< 0x08048d53      7445           je 0x8048d9a               
|   |||||   0x08048d55      80bc24030300.  cmp byte [esp + 0x303], 0x2c ; [0x2c:1]=9 ; ','
|  ,======< 0x08048d5d      743b           je 0x8048d9a               
|  ||||||   0x08048d5f      80bc24030300.  cmp byte [esp + 0x303], 0x3b ; [0x3b:1]=0 ; ';'
| ,=======< 0x08048d67      7431           je 0x8048d9a               
| |||||||   0x08048d69      80bc24030300.  cmp byte [esp + 0x303], 0xa ; [0xa:1]=0
| ========< 0x08048d71      7427           je 0x8048d9a               
| |||||||   0x08048d73      80bc24030300.  cmp byte [esp + 0x303], 0xd ; [0xd:1]=0
| ========< 0x08048d7b      741d           je 0x8048d9a               
| |||||||   0x08048d7d      c70424959904.  mov dword [esp], str.Wrong_symbols_in_line. ; [0x8049995:4]=0x6e6f7257  LEA str.Wrong_symbols_in_line. ; "Wrong symbols in line." @ 0x8049995
| |||||||   0x08048d84      e8f7faffff     call sym.imp.puts
| |||||||   0x08048d89      e8e6fcffff     call sym.close_free
| |||||||   0x08048d8e      c70424010000.  mov dword [esp], 1
| |||||||   0x08048d95      e826fbffff     call sym.imp.exit
| |||||||   ; XREFS: JMP 0x08048d3f  JMP 0x08048d49  JMP 0x08048d53  
| |||||||   ; XREFS: JMP 0x08048d5d  JMP 0x08048d67  JMP 0x08048d71  
| |||||||   ; XREFS: JMP 0x08048d7b  
| `````---> 0x08048d9a      80bc24030300.  cmp byte [esp + 0x303], 0x3b ; [0x3b:1]=0 ; ';'
|     ,===< 0x08048da2      7508           jne 0x8048dac              
|     |||   0x08048da4      838424d80200.  add dword [esp + 0x2d8], 1
|     |||   ; JMP XREF from 0x08048d2b (sym.main)
|     |||   ; JMP XREF from 0x08048da2 (sym.main)
|     `-`-> 0x08048dac      a18cb00408     mov eax, dword [obj.fid]    ; [0x804b08c:4]=0x6e694c2f  LEA obj.fid ; "/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b08c
|      |    0x08048db1      890424         mov dword [esp], eax
|      |    0x08048db4      e8a7fbffff     call sym.imp.fgetc
|      |    0x08048db9      888424030300.  mov byte [esp + 0x303], al
|      |    0x08048dc0      80bc24030300.  cmp byte [esp + 0x303], 0xff ; [0xff:1]=8
|      `==< 0x08048dc8      0f855fffffff   jne 0x8048d2d              
|           0x08048dce      a18cb00408     mov eax, dword [obj.fid]    ; [0x804b08c:4]=0x6e694c2f  LEA obj.fid ; "/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b08c
|           0x08048dd3      890424         mov dword [esp], eax
|           0x08048dd6      e845faffff     call sym.imp.rewind
|           0x08048ddb      c744241c0000.  mov dword [esp + 0x1c], 0
|           0x08048de3      c74424180000.  mov dword [esp + 0x18], 0
|           0x08048deb      c74424140000.  mov dword [esp + 0x14], 0
|           0x08048df3      8b8424f80200.  mov eax, dword [esp + 0x2f8] ; [0x2f8:4]=344
|           0x08048dfa      89442410       mov dword [esp + 0x10], eax
|           0x08048dfe      8b8424f40200.  mov eax, dword [esp + 0x2f4] ; [0x2f4:4]=18
|           0x08048e05      8944240c       mov dword [esp + 0xc], eax
|           0x08048e09      8b8424f00200.  mov eax, dword [esp + 0x2f0] ; [0x2f0:4]=0
|           0x08048e10      89442408       mov dword [esp + 8], eax
|           0x08048e14      8b8424ec0200.  mov eax, dword [esp + 0x2ec] ; [0x2ec:4]=0
|           0x08048e1b      89442404       mov dword [esp + 4], eax
|           0x08048e1f      8b8424e40200.  mov eax, dword [esp + 0x2e4] ; [0x2e4:4]=18
|           0x08048e26      890424         mov dword [esp], eax
|           0x08048e29      e812fbffff     call sym.imp.mysql_real_connect
|           0x08048e2e      85c0           test eax, eax
|       ,=< 0x08048e30      751b           jne 0x8048e4d              
|       |   0x08048e32      c70424ac9904.  mov dword [esp], str.Cannot_connect_to_database ; [0x80499ac:4]=0x6e6e6143  LEA str.Cannot_connect_to_database ; "Cannot connect to database" @ 0x80499ac
|       |   0x08048e39      e842faffff     call sym.imp.puts
|       |   0x08048e3e      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|       |   0x08048e45      890424         mov dword [esp], eax
|       |   0x08048e48      e856fcffff     call sym.finish_with_error
|       |   ; JMP XREF from 0x08048e30 (sym.main)
|       `-> 0x08048e4d      b8c8990408     mov eax, str.UPDATE_ActiveTAN_SET_ActiveTAN_Status___0_WHERE_ActiveTAN_number_____AND_ActiveTAN_accountNo____ ; "UPDATE ActiveTAN SET ActiveTAN_Status = 0 WHERE ActiveTAN_number = ? AND ActiveTAN_accountNo = ?" @ 0x80499c8
|           0x08048e52      89442408       mov dword [esp + 8], eax
|           0x08048e56      c74424040002.  mov dword [esp + 4], 0x200  ; [0x200:4]=0
|           0x08048e5e      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|           0x08048e65      890424         mov dword [esp], eax
|           0x08048e68      e8c3faffff     call sym.imp.snprintf
|           0x08048e6d      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|           0x08048e74      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|           0x08048e7c      89c2           mov edx, eax
|           0x08048e7e      b800000000     mov eax, 0
|           0x08048e83      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|           0x08048e87      89d7           mov edi, edx
|           0x08048e89      f2ae           repne scasb al, byte es:[edi]
|           0x08048e8b      89c8           mov eax, ecx
|           0x08048e8d      f7d0           not eax
|           0x08048e8f      83e801         sub eax, 1
|           0x08048e92      89442408       mov dword [esp + 8], eax
|           0x08048e96      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|           0x08048e9d      89442404       mov dword [esp + 4], eax
|           0x08048ea1      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|           0x08048ea8      890424         mov dword [esp], eax
|           0x08048eab      e8b0f9ffff     call sym.imp.mysql_stmt_prepare
|           0x08048eb0      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|           0x08048eb4      89c3           mov ebx, eax
|           0x08048eb6      b800000000     mov eax, 0
|           0x08048ebb      baa0000000     mov edx, 0xa0
|           0x08048ec0      89df           mov edi, ebx
|           0x08048ec2      89d1           mov ecx, edx
|           0x08048ec4      f3ab           rep stosd dword es:[edi], eax
|           0x08048ec6      c744246cfe00.  mov dword [esp + 0x6c], 0xfe ; [0xfe:4]=0x9cd80804 
|           0x08048ece      a194b00408     mov eax, dword [obj.transactiontan] ; [0x804b094:4]=0x2e362e34  LEA obj.transactiontan ; "4.6.3-1ubuntu5) 4.6.3" @ 0x804b094
|           0x08048ed3      89442440       mov dword [esp + 0x40], eax
|           0x08048ed7      a194b00408     mov eax, dword [obj.transactiontan] ; [0x804b094:4]=0x2e362e34  LEA obj.transactiontan ; "4.6.3-1ubuntu5) 4.6.3" @ 0x804b094
|           0x08048edc      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|           0x08048ee4      89c2           mov edx, eax
|           0x08048ee6      b800000000     mov eax, 0
|           0x08048eeb      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|           0x08048eef      89d7           mov edi, edx
|           0x08048ef1      f2ae           repne scasb al, byte es:[edi]
|           0x08048ef3      89c8           mov eax, ecx
|           0x08048ef5      f7d0           not eax
|           0x08048ef7      83e801         sub eax, 1
|           0x08048efa      89442458       mov dword [esp + 0x58], eax
|           0x08048efe      c78424ac0000.  mov dword [esp + 0xac], 0xfe ; [0xfe:4]=0x9cd80804 
|           0x08048f09      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|           0x08048f0e      898424800000.  mov dword [esp + 0x80], eax
|           0x08048f15      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|           0x08048f1a      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|           0x08048f22      89c2           mov edx, eax
|           0x08048f24      b800000000     mov eax, 0
|           0x08048f29      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|           0x08048f2d      89d7           mov edi, edx
|           0x08048f2f      f2ae           repne scasb al, byte es:[edi]
|           0x08048f31      89c8           mov eax, ecx
|           0x08048f33      f7d0           not eax
|           0x08048f35      83e801         sub eax, 1
|           0x08048f38      898424980000.  mov dword [esp + 0x98], eax
|           0x08048f3f      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|           0x08048f43      89442404       mov dword [esp + 4], eax
|           0x08048f47      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|           0x08048f4e      890424         mov dword [esp], eax
|           0x08048f51      e85afaffff     call sym.imp.mysql_stmt_bind_param
|           0x08048f56      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|           0x08048f5d      890424         mov dword [esp], eax
|           0x08048f60      e83bfaffff     call sym.imp.mysql_stmt_execute
|           0x08048f65      85c0           test eax, eax
|       ,=< 0x08048f67      0f8481080000   je 0x80497ee               
|       |   0x08048f6d      c70424299a04.  mov dword [esp], str.Error_while_updating_the_TAN ; [0x8049a29:4]=0x6f727245  LEA str.Error_while_updating_the_TAN ; "Error while updating the TAN" @ 0x8049a29
|       |   0x08048f74      e807f9ffff     call sym.imp.puts
|       |   0x08048f79      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|       |   0x08048f80      890424         mov dword [esp], eax
|       |   0x08048f83      e81bfbffff     call sym.finish_with_error
|      ,==< 0x08048f88      e961080000     jmp 0x80497ee              
|      ||   ; JMP XREF from 0x080497fe (sym.main)
|     .---> 0x08048f8d      ba469a0408     mov edx, str._ld__lf_       ; "%ld,%lf;" @ 0x8049a46
|     |||   0x08048f92      a18cb00408     mov eax, dword [obj.fid]    ; [0x804b08c:4]=0x6e694c2f  LEA obj.fid ; "/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b08c
|     |||   0x08048f97      8d8c24b80200.  lea ecx, [esp + 0x2b8]      ; 0x2b8 
|     |||   0x08048f9e      894c240c       mov dword [esp + 0xc], ecx
|     |||   0x08048fa2      8d8c24d00200.  lea ecx, [esp + 0x2d0]      ; 0x2d0 
|     |||   0x08048fa9      894c2408       mov dword [esp + 8], ecx
|     |||   0x08048fad      89542404       mov dword [esp + 4], edx
|     |||   0x08048fb1      890424         mov dword [esp], eax
|     |||   0x08048fb4      e857f9ffff     call sym.imp.fscanf
|     |||   0x08048fb9      83f802         cmp eax, 2
|    ,====< 0x08048fbc      0f8544080000   jne 0x8049806              
|    ||||   0x08048fc2      dd8424b80200.  fld qword [esp + 0x2b8]
|    ||||   0x08048fc9      dd05d09c0408   fld qword [0x8049cd0]
|    ||||   0x08048fcf      d9c9           fxch st(1)
|    ||||   0x08048fd1      dfe9           fucompi st(1)
|    ||||   0x08048fd3      ddd8           fstp st(0)
|    ||||   0x08048fd5      0f93c0         setae al
|    ||||   0x08048fd8      84c0           test al, al
|   ,=====< 0x08048fda      7418           je 0x8048ff4               
|   |||||   0x08048fdc      ba4f9a0408     mov edx, str.PENDING        ; "PENDING" @ 0x8049a4f
|   |||||   0x08048fe1      8d8424b80500.  lea eax, [esp + 0x5b8]      ; 0x5b8 
|   |||||   0x08048fe8      8b0a           mov ecx, dword [edx]
|   |||||   0x08048fea      8908           mov dword [eax], ecx
|   |||||   0x08048fec      8b5204         mov edx, dword [edx + 4]    ; [0x4:4]=0x10101 
|   |||||   0x08048fef      895004         mov dword [eax + 4], edx
|  ,======< 0x08048ff2      eb1d           jmp 0x8049011              
|  ||||||   ; JMP XREF from 0x08048fda (sym.main)
|  |`-----> 0x08048ff4      ba579a0408     mov edx, str.APPROVED       ; "APPROVED" @ 0x8049a57
|  | ||||   0x08048ff9      8d8424b80500.  lea eax, [esp + 0x5b8]      ; 0x5b8 
|  | ||||   0x08049000      8b0a           mov ecx, dword [edx]
|  | ||||   0x08049002      8908           mov dword [eax], ecx
|  | ||||   0x08049004      8b4a04         mov ecx, dword [edx + 4]    ; [0x4:4]=0x10101 
|  | ||||   0x08049007      894804         mov dword [eax + 4], ecx
|  | ||||   0x0804900a      0fb65208       movzx edx, byte [edx + 8]   ; [0x8:1]=0
|  | ||||   0x0804900e      885008         mov byte [eax + 8], dl
|  | ||||   ; JMP XREF from 0x08048ff2 (sym.main)
|  `------> 0x08049011      b8609a0408     mov eax, str.SELECT_Account_bal_FROM_Account_WHERE_Account_no____and_Account_bal_____ ; "SELECT Account_bal FROM Account WHERE Account_no= ? and Account_bal >= ?" @ 0x8049a60
|    ||||   0x08049016      89442408       mov dword [esp + 8], eax
|    ||||   0x0804901a      c74424040002.  mov dword [esp + 4], 0x200  ; [0x200:4]=0
|    ||||   0x08049022      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x08049029      890424         mov dword [esp], eax
|    ||||   0x0804902c      e8fff8ffff     call sym.imp.snprintf
|    ||||   0x08049031      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x08049038      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x08049040      89c2           mov edx, eax
|    ||||   0x08049042      b800000000     mov eax, 0
|    ||||   0x08049047      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x0804904b      89d7           mov edi, edx
|    ||||   0x0804904d      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x0804904f      89c8           mov eax, ecx
|    ||||   0x08049051      f7d0           not eax
|    ||||   0x08049053      83e801         sub eax, 1
|    ||||   0x08049056      89442408       mov dword [esp + 8], eax
|    ||||   0x0804905a      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x08049061      89442404       mov dword [esp + 4], eax
|    ||||   0x08049065      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x0804906c      890424         mov dword [esp], eax
|    ||||   0x0804906f      e8ecf7ffff     call sym.imp.mysql_stmt_prepare
|    ||||   0x08049074      c744246cfe00.  mov dword [esp + 0x6c], 0xfe ; [0xfe:4]=0x9cd80804 
|    ||||   0x0804907c      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|    ||||   0x08049081      89442440       mov dword [esp + 0x40], eax
|    ||||   0x08049085      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|    ||||   0x0804908a      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x08049092      89c2           mov edx, eax
|    ||||   0x08049094      b800000000     mov eax, 0
|    ||||   0x08049099      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x0804909d      89d7           mov edi, edx
|    ||||   0x0804909f      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x080490a1      89c8           mov eax, ecx
|    ||||   0x080490a3      f7d0           not eax
|    ||||   0x080490a5      83e801         sub eax, 1
|    ||||   0x080490a8      89442458       mov dword [esp + 0x58], eax
|    ||||   0x080490ac      c78424ac0000.  mov dword [esp + 0xac], 5
|    ||||   0x080490b7      8d8424b80200.  lea eax, [esp + 0x2b8]      ; 0x2b8 
|    ||||   0x080490be      898424800000.  mov dword [esp + 0x80], eax
|    ||||   0x080490c5      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|    ||||   0x080490c9      89442404       mov dword [esp + 4], eax
|    ||||   0x080490cd      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x080490d4      890424         mov dword [esp], eax
|    ||||   0x080490d7      e8d4f8ffff     call sym.imp.mysql_stmt_bind_param
|    ||||   0x080490dc      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x080490e3      890424         mov dword [esp], eax
|    ||||   0x080490e6      e8b5f8ffff     call sym.imp.mysql_stmt_execute
|    ||||   0x080490eb      85c0           test eax, eax
|   ,=====< 0x080490ed      741b           je 0x804910a               
|   |||||   0x080490ef      c70424a99a04.  mov dword [esp], str.MySQL_error. ; [0x8049aa9:4]=0x5153794d  LEA str.MySQL_error. ; "MySQL error." @ 0x8049aa9
|   |||||   0x080490f6      e885f7ffff     call sym.imp.puts
|   |||||   0x080490fb      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|   |||||   0x08049102      890424         mov dword [esp], eax
|   |||||   0x08049105      e899f9ffff     call sym.finish_with_error
|   |||||   ; JMP XREF from 0x080490ed (sym.main)
|   `-----> 0x0804910a      c744246c0500.  mov dword [esp + 0x6c], 5
|    ||||   0x08049112      8d8424c00200.  lea eax, [esp + 0x2c0]      ; 0x2c0 
|    ||||   0x08049119      89442440       mov dword [esp + 0x40], eax
|    ||||   0x0804911d      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|    ||||   0x08049121      89442404       mov dword [esp + 4], eax
|    ||||   0x08049125      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x0804912c      890424         mov dword [esp], eax
|    ||||   0x0804912f      e89cf7ffff     call sym.imp.mysql_stmt_bind_result
|    ||||   0x08049134      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x0804913b      890424         mov dword [esp], eax
|    ||||   0x0804913e      e84df7ffff     call sym.imp.mysql_stmt_store_result
|    ||||   0x08049143      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x0804914a      890424         mov dword [esp], eax
|    ||||   0x0804914d      e8eef6ffff     call sym.imp.mysql_stmt_fetch
|    ||||   0x08049152      83f864         cmp eax, 0x64               ; 'd'
|   ,=====< 0x08049155      751b           jne 0x8049172              
|   |||||   0x08049157      c70424b89a04.  mov dword [esp], str.Not_enough_balance_in_user_account ; [0x8049ab8:4]=0x20746f4e  LEA str.Not_enough_balance_in_user_account ; "Not enough balance in user account" @ 0x8049ab8
|   |||||   0x0804915e      e81df7ffff     call sym.imp.puts
|   |||||   0x08049163      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|   |||||   0x0804916a      890424         mov dword [esp], eax
|   |||||   0x0804916d      e831f9ffff     call sym.finish_with_error
|   |||||   ; JMP XREF from 0x08049155 (sym.main)
|   `-----> 0x08049172      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x08049179      890424         mov dword [esp], eax
|    ||||   0x0804917c      e80ff8ffff     call sym.imp.mysql_stmt_free_result
|    ||||   0x08049181      b8dc9a0408     mov eax, str.SELECT_Account_bal_FROM_Account_WHERE_Account_no___ ; "SELECT Account_bal FROM Account WHERE Account_no= ?" @ 0x8049adc
|    ||||   0x08049186      89442408       mov dword [esp + 8], eax
|    ||||   0x0804918a      c74424040002.  mov dword [esp + 4], 0x200  ; [0x200:4]=0
|    ||||   0x08049192      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x08049199      890424         mov dword [esp], eax
|    ||||   0x0804919c      e88ff7ffff     call sym.imp.snprintf
|    ||||   0x080491a1      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x080491a8      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x080491b0      89c2           mov edx, eax
|    ||||   0x080491b2      b800000000     mov eax, 0
|    ||||   0x080491b7      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x080491bb      89d7           mov edi, edx
|    ||||   0x080491bd      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x080491bf      89c8           mov eax, ecx
|    ||||   0x080491c1      f7d0           not eax
|    ||||   0x080491c3      83e801         sub eax, 1
|    ||||   0x080491c6      89442408       mov dword [esp + 8], eax
|    ||||   0x080491ca      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x080491d1      89442404       mov dword [esp + 4], eax
|    ||||   0x080491d5      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x080491dc      890424         mov dword [esp], eax
|    ||||   0x080491df      e87cf6ffff     call sym.imp.mysql_stmt_prepare
|    ||||   0x080491e4      c744246c0300.  mov dword [esp + 0x6c], 3
|    ||||   0x080491ec      8d8424d00200.  lea eax, [esp + 0x2d0]      ; 0x2d0 
|    ||||   0x080491f3      89442440       mov dword [esp + 0x40], eax
|    ||||   0x080491f7      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|    ||||   0x080491fb      89442404       mov dword [esp + 4], eax
|    ||||   0x080491ff      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x08049206      890424         mov dword [esp], eax
|    ||||   0x08049209      e8a2f7ffff     call sym.imp.mysql_stmt_bind_param
|    ||||   0x0804920e      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x08049215      890424         mov dword [esp], eax
|    ||||   0x08049218      e883f7ffff     call sym.imp.mysql_stmt_execute
|    ||||   0x0804921d      85c0           test eax, eax
|   ,=====< 0x0804921f      741b           je 0x804923c               
|   |||||   0x08049221      c70424a99a04.  mov dword [esp], str.MySQL_error. ; [0x8049aa9:4]=0x5153794d  LEA str.MySQL_error. ; "MySQL error." @ 0x8049aa9
|   |||||   0x08049228      e853f6ffff     call sym.imp.puts
|   |||||   0x0804922d      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|   |||||   0x08049234      890424         mov dword [esp], eax
|   |||||   0x08049237      e867f8ffff     call sym.finish_with_error
|   |||||   ; JMP XREF from 0x0804921f (sym.main)
|   `-----> 0x0804923c      c744246c0500.  mov dword [esp + 0x6c], 5
|    ||||   0x08049244      8d8424c80200.  lea eax, [esp + 0x2c8]      ; 0x2c8 
|    ||||   0x0804924b      89442440       mov dword [esp + 0x40], eax
|    ||||   0x0804924f      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|    ||||   0x08049253      89442404       mov dword [esp + 4], eax
|    ||||   0x08049257      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x0804925e      890424         mov dword [esp], eax
|    ||||   0x08049261      e86af6ffff     call sym.imp.mysql_stmt_bind_result
|    ||||   0x08049266      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x0804926d      890424         mov dword [esp], eax
|    ||||   0x08049270      e81bf6ffff     call sym.imp.mysql_stmt_store_result
|    ||||   0x08049275      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x0804927c      890424         mov dword [esp], eax
|    ||||   0x0804927f      e8bcf5ffff     call sym.imp.mysql_stmt_fetch
|    ||||   0x08049284      83f864         cmp eax, 0x64               ; 'd'
|   ,=====< 0x08049287      751b           jne 0x80492a4              
|   |||||   0x08049289      c70424109b04.  mov dword [esp], str.Wrong_receiver_account_number. ; [0x8049b10:4]=0x6e6f7257  LEA str.Wrong_receiver_account_number. ; "Wrong receiver account number." @ 0x8049b10
|   |||||   0x08049290      e8ebf5ffff     call sym.imp.puts
|   |||||   0x08049295      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|   |||||   0x0804929c      890424         mov dword [esp], eax
|   |||||   0x0804929f      e8fff7ffff     call sym.finish_with_error
|   |||||   ; JMP XREF from 0x08049287 (sym.main)
|   `-----> 0x080492a4      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x080492ab      890424         mov dword [esp], eax
|    ||||   0x080492ae      e8ddf6ffff     call sym.imp.mysql_stmt_free_result
|    ||||   0x080492b3      8d8424b80500.  lea eax, [esp + 0x5b8]      ; 0x5b8 
|    ||||   0x080492ba      89c2           mov edx, eax
|    ||||   0x080492bc      b84f9a0408     mov eax, str.PENDING        ; "PENDING" @ 0x8049a4f
|    ||||   0x080492c1      b908000000     mov ecx, 8
|    ||||   0x080492c6      89d6           mov esi, edx
|    ||||   0x080492c8      89c7           mov edi, eax
|    ||||   0x080492ca      f3a6           repe cmpsb byte [esi], byte ptr es:[edi] ; [0x170000001c:1]=255 ; 28
|    ||||   0x080492cc      0f97c2         seta dl
|    ||||   0x080492cf      0f92c0         setb al
|    ||||   0x080492d2      89d1           mov ecx, edx
|    ||||   0x080492d4      28c1           sub cl, al
|    ||||   0x080492d6      89c8           mov eax, ecx
|    ||||   0x080492d8      0fbec0         movsx eax, al
|    ||||   0x080492db      85c0           test eax, eax
|   ,=====< 0x080492dd      0f844d020000   je 0x8049530               
|   |||||   0x080492e3      b8309b0408     mov eax, str.UPDATE_Account_SET_Account_bal___WHERE_Account_no__ ; "UPDATE Account SET Account_bal=? WHERE Account_no=?" @ 0x8049b30
|   |||||   0x080492e8      89442408       mov dword [esp + 8], eax
|   |||||   0x080492ec      c74424040002.  mov dword [esp + 4], 0x200  ; [0x200:4]=0
|   |||||   0x080492f4      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|   |||||   0x080492fb      890424         mov dword [esp], eax
|   |||||   0x080492fe      e82df6ffff     call sym.imp.snprintf
|   |||||   0x08049303      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|   |||||   0x0804930a      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|   |||||   0x08049312      89c2           mov edx, eax
|   |||||   0x08049314      b800000000     mov eax, 0
|   |||||   0x08049319      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|   |||||   0x0804931d      89d7           mov edi, edx
|   |||||   0x0804931f      f2ae           repne scasb al, byte es:[edi]
|   |||||   0x08049321      89c8           mov eax, ecx
|   |||||   0x08049323      f7d0           not eax
|   |||||   0x08049325      83e801         sub eax, 1
|   |||||   0x08049328      89442408       mov dword [esp + 8], eax
|   |||||   0x0804932c      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|   |||||   0x08049333      89442404       mov dword [esp + 4], eax
|   |||||   0x08049337      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|   |||||   0x0804933e      890424         mov dword [esp], eax
|   |||||   0x08049341      e81af5ffff     call sym.imp.mysql_stmt_prepare
|   |||||   0x08049346      dd8424c00200.  fld qword [esp + 0x2c0]
|   |||||   0x0804934d      dd8424b80200.  fld qword [esp + 0x2b8]
|   |||||   0x08049354      dee9           fsubp st(1)
|   |||||   0x08049356      dd9c24c00200.  fstp qword [esp + 0x2c0]
|   |||||   0x0804935d      c744246c0500.  mov dword [esp + 0x6c], 5
|   |||||   0x08049365      8d8424c00200.  lea eax, [esp + 0x2c0]      ; 0x2c0 
|   |||||   0x0804936c      89442440       mov dword [esp + 0x40], eax
|   |||||   0x08049370      c78424ac0000.  mov dword [esp + 0xac], 0xfe ; [0xfe:4]=0x9cd80804 
|   |||||   0x0804937b      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|   |||||   0x08049380      898424800000.  mov dword [esp + 0x80], eax
|   |||||   0x08049387      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|   |||||   0x0804938c      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|   |||||   0x08049394      89c2           mov edx, eax
|   |||||   0x08049396      b800000000     mov eax, 0
|   |||||   0x0804939b      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|   |||||   0x0804939f      89d7           mov edi, edx
|   |||||   0x080493a1      f2ae           repne scasb al, byte es:[edi]
|   |||||   0x080493a3      89c8           mov eax, ecx
|   |||||   0x080493a5      f7d0           not eax
|   |||||   0x080493a7      83e801         sub eax, 1
|   |||||   0x080493aa      898424980000.  mov dword [esp + 0x98], eax
|   |||||   0x080493b1      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|   |||||   0x080493b5      89442404       mov dword [esp + 4], eax
|   |||||   0x080493b9      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|   |||||   0x080493c0      890424         mov dword [esp], eax
|   |||||   0x080493c3      e8e8f5ffff     call sym.imp.mysql_stmt_bind_param
|   |||||   0x080493c8      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|   |||||   0x080493cf      890424         mov dword [esp], eax
|   |||||   0x080493d2      e8c9f5ffff     call sym.imp.mysql_stmt_execute
|   |||||   0x080493d7      85c0           test eax, eax
|  ,======< 0x080493d9      0f8534010000   jne 0x8049513              
|  ||||||   0x080493df      b8309b0408     mov eax, str.UPDATE_Account_SET_Account_bal___WHERE_Account_no__ ; "UPDATE Account SET Account_bal=? WHERE Account_no=?" @ 0x8049b30
|  ||||||   0x080493e4      89442408       mov dword [esp + 8], eax
|  ||||||   0x080493e8      c74424040002.  mov dword [esp + 4], 0x200  ; [0x200:4]=0
|  ||||||   0x080493f0      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|  ||||||   0x080493f7      890424         mov dword [esp], eax
|  ||||||   0x080493fa      e831f5ffff     call sym.imp.snprintf
|  ||||||   0x080493ff      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|  ||||||   0x08049406      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|  ||||||   0x0804940e      89c2           mov edx, eax
|  ||||||   0x08049410      b800000000     mov eax, 0
|  ||||||   0x08049415      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|  ||||||   0x08049419      89d7           mov edi, edx
|  ||||||   0x0804941b      f2ae           repne scasb al, byte es:[edi]
|  ||||||   0x0804941d      89c8           mov eax, ecx
|  ||||||   0x0804941f      f7d0           not eax
|  ||||||   0x08049421      83e801         sub eax, 1
|  ||||||   0x08049424      89442408       mov dword [esp + 8], eax
|  ||||||   0x08049428      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|  ||||||   0x0804942f      89442404       mov dword [esp + 4], eax
|  ||||||   0x08049433      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|  ||||||   0x0804943a      890424         mov dword [esp], eax
|  ||||||   0x0804943d      e81ef4ffff     call sym.imp.mysql_stmt_prepare
|  ||||||   0x08049442      dd8424c80200.  fld qword [esp + 0x2c8]
|  ||||||   0x08049449      dd8424b80200.  fld qword [esp + 0x2b8]
|  ||||||   0x08049450      dec1           faddp st(1)
|  ||||||   0x08049452      dd9c24c80200.  fstp qword [esp + 0x2c8]
|  ||||||   0x08049459      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|  ||||||   0x0804945d      89c3           mov ebx, eax
|  ||||||   0x0804945f      b800000000     mov eax, 0
|  ||||||   0x08049464      baa0000000     mov edx, 0xa0
|  ||||||   0x08049469      89df           mov edi, ebx
|  ||||||   0x0804946b      89d1           mov ecx, edx
|  ||||||   0x0804946d      f3ab           rep stosd dword es:[edi], eax
|  ||||||   0x0804946f      c744246c0500.  mov dword [esp + 0x6c], 5
|  ||||||   0x08049477      8d8424c80200.  lea eax, [esp + 0x2c8]      ; 0x2c8 
|  ||||||   0x0804947e      89442440       mov dword [esp + 0x40], eax
|  ||||||   0x08049482      c78424ac0000.  mov dword [esp + 0xac], 3
|  ||||||   0x0804948d      8d8424d00200.  lea eax, [esp + 0x2d0]      ; 0x2d0 
|  ||||||   0x08049494      898424800000.  mov dword [esp + 0x80], eax
|  ||||||   0x0804949b      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|  ||||||   0x0804949f      89442404       mov dword [esp + 4], eax
|  ||||||   0x080494a3      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|  ||||||   0x080494aa      890424         mov dword [esp], eax
|  ||||||   0x080494ad      e8fef4ffff     call sym.imp.mysql_stmt_bind_param
|  ||||||   0x080494b2      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|  ||||||   0x080494b9      890424         mov dword [esp], eax
|  ||||||   0x080494bc      e8dff4ffff     call sym.imp.mysql_stmt_execute
|  ||||||   0x080494c1      85c0           test eax, eax
| ,=======< 0x080494c3      7525           jne 0x80494ea              
| |||||||   0x080494c5      8b9424d00200.  mov edx, dword [esp + 0x2d0] ; [0x2d0:4]=0
| |||||||   0x080494cc      dd8424b80200.  fld qword [esp + 0x2b8]
| |||||||   0x080494d3      b8649b0408     mov eax, str.Money_transfered:__lf_euro_to__ld_account._n ; "Money transfered: %lf euro to %ld account.." @ 0x8049b64
| |||||||   0x080494d8      8954240c       mov dword [esp + 0xc], edx
| |||||||   0x080494dc      dd5c2404       fstp qword [esp + 4]
| |||||||   0x080494e0      890424         mov dword [esp], eax
| |||||||   0x080494e3      e8f8f2ffff     call sym.imp.printf
| ========< 0x080494e8      eb46           jmp 0x8049530              
| |||||||   ; JMP XREF from 0x080494c3 (sym.main)
| `-------> 0x080494ea      b8909b0408     mov eax, str.Error_with_money_transfer_n_s_n ; "Error with money transfer.%s." @ 0x8049b90
|  ||||||   0x080494ef      8d9424040300.  lea edx, [esp + 0x304]      ; 0x304 
|  ||||||   0x080494f6      89542404       mov dword [esp + 4], edx
|  ||||||   0x080494fa      890424         mov dword [esp], eax
|  ||||||   0x080494fd      e8def2ffff     call sym.imp.printf
|  ||||||   0x08049502      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|  ||||||   0x08049509      890424         mov dword [esp], eax
|  ||||||   0x0804950c      e892f5ffff     call sym.finish_with_error
| ,=======< 0x08049511      eb1d           jmp 0x8049530              
| |||||||   ; JMP XREF from 0x080493d9 (sym.main)
| |`------> 0x08049513      baae9b0408     mov edx, str.REJECTED       ; "REJECTED" @ 0x8049bae
| | |||||   0x08049518      8d8424b80500.  lea eax, [esp + 0x5b8]      ; 0x5b8 
| | |||||   0x0804951f      8b0a           mov ecx, dword [edx]
| | |||||   0x08049521      8908           mov dword [eax], ecx
| | |||||   0x08049523      8b4a04         mov ecx, dword [edx + 4]    ; [0x4:4]=0x10101 
| | |||||   0x08049526      894804         mov dword [eax + 4], ecx
| | |||||   0x08049529      0fb65208       movzx edx, byte [edx + 8]   ; [0x8:1]=0
| | |||||   0x0804952d      885008         mov byte [eax + 8], dl
| | |||||   ; JMP XREF from 0x08049511 (sym.main)
| | |||||   ; JMP XREF from 0x080494e8 (sym.main)
| | |||||   ; JMP XREF from 0x080492dd (sym.main)
| `-`-----> 0x08049530      8d8424d40200.  lea eax, [esp + 0x2d4]      ; 0x2d4 
|    ||||   0x08049537      890424         mov dword [esp], eax
|    ||||   0x0804953a      e8d1f2ffff     call sym.imp.time
|    ||||   0x0804953f      8d8424d40200.  lea eax, [esp + 0x2d4]      ; 0x2d4 
|    ||||   0x08049546      890424         mov dword [esp], eax
|    ||||   0x08049549      e8b2f3ffff     call sym.imp.localtime
|    ||||   0x0804954e      898424fc0200.  mov dword [esp + 0x2fc], eax
|    ||||   0x08049555      8b9424fc0200.  mov edx, dword [esp + 0x2fc] ; [0x2fc:4]=0
|    ||||   0x0804955c      b8b79b0408     mov eax, str._F___X         ; "%F  %X" @ 0x8049bb7
|    ||||   0x08049561      8954240c       mov dword [esp + 0xc], edx
|    ||||   0x08049565      89442408       mov dword [esp + 8], eax
|    ||||   0x08049569      c74424045000.  mov dword [esp + 4], 0x50   ; [0x50:4]=4 ; 'P'
|    ||||   0x08049571      8d8424680500.  lea eax, [esp + 0x568]      ; 0x568 
|    ||||   0x08049578      890424         mov dword [esp], eax
|    ||||   0x0804957b      e870f3ffff     call sym.imp.strftime
|    ||||   0x08049580      b8c09b0408     mov eax, str.INSERT_INTO_Txn___Txn_amount__Txn_TAN_used__Txn_date__Txn_SndAccountNo__Txn_RcvAccountNo__Txn_ApprovalStatus__Txn_Status__Txn_description__VALUES___________________ ; "INSERT INTO Txn ( Txn_amount, Txn_TAN_used, Txn_date, Txn_SndAccountNo, Txn_RcvAccountNo, Txn_ApprovalStatus, Txn_Status, Txn_description) VALUES (?,?,?,?,?,?,?, ?)" @ 0x8049bc0
|    ||||   0x08049585      89442408       mov dword [esp + 8], eax
|    ||||   0x08049589      c74424040002.  mov dword [esp + 4], 0x200  ; [0x200:4]=0
|    ||||   0x08049591      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x08049598      890424         mov dword [esp], eax
|    ||||   0x0804959b      e890f3ffff     call sym.imp.snprintf
|    ||||   0x080495a0      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x080495a7      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x080495af      89c2           mov edx, eax
|    ||||   0x080495b1      b800000000     mov eax, 0
|    ||||   0x080495b6      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x080495ba      89d7           mov edi, edx
|    ||||   0x080495bc      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x080495be      89c8           mov eax, ecx
|    ||||   0x080495c0      f7d0           not eax
|    ||||   0x080495c2      83e801         sub eax, 1
|    ||||   0x080495c5      89442408       mov dword [esp + 8], eax
|    ||||   0x080495c9      8d8424040300.  lea eax, [esp + 0x304]      ; 0x304 
|    ||||   0x080495d0      89442404       mov dword [esp + 4], eax
|    ||||   0x080495d4      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x080495db      890424         mov dword [esp], eax
|    ||||   0x080495de      e87df2ffff     call sym.imp.mysql_stmt_prepare
|    ||||   0x080495e3      c744246c0500.  mov dword [esp + 0x6c], 5
|    ||||   0x080495eb      8d8424b80200.  lea eax, [esp + 0x2b8]      ; 0x2b8 
|    ||||   0x080495f2      89442440       mov dword [esp + 0x40], eax
|    ||||   0x080495f6      c78424ac0000.  mov dword [esp + 0xac], 0xfe ; [0xfe:4]=0x9cd80804 
|    ||||   0x08049601      a194b00408     mov eax, dword [obj.transactiontan] ; [0x804b094:4]=0x2e362e34  LEA obj.transactiontan ; "4.6.3-1ubuntu5) 4.6.3" @ 0x804b094
|    ||||   0x08049606      898424800000.  mov dword [esp + 0x80], eax
|    ||||   0x0804960d      a194b00408     mov eax, dword [obj.transactiontan] ; [0x804b094:4]=0x2e362e34  LEA obj.transactiontan ; "4.6.3-1ubuntu5) 4.6.3" @ 0x804b094
|    ||||   0x08049612      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x0804961a      89c2           mov edx, eax
|    ||||   0x0804961c      b800000000     mov eax, 0
|    ||||   0x08049621      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x08049625      89d7           mov edi, edx
|    ||||   0x08049627      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x08049629      89c8           mov eax, ecx
|    ||||   0x0804962b      f7d0           not eax
|    ||||   0x0804962d      83e801         sub eax, 1
|    ||||   0x08049630      898424980000.  mov dword [esp + 0x98], eax
|    ||||   0x08049637      c78424ec0000.  mov dword [esp + 0xec], 0xfe ; [0xfe:4]=0x9cd80804 
|    ||||   0x08049642      8d8424680500.  lea eax, [esp + 0x568]      ; 0x568 
|    ||||   0x08049649      898424c00000.  mov dword [esp + 0xc0], eax
|    ||||   0x08049650      8d8424680500.  lea eax, [esp + 0x568]      ; 0x568 
|    ||||   0x08049657      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x0804965f      89c2           mov edx, eax
|    ||||   0x08049661      b800000000     mov eax, 0
|    ||||   0x08049666      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x0804966a      89d7           mov edi, edx
|    ||||   0x0804966c      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x0804966e      89c8           mov eax, ecx
|    ||||   0x08049670      f7d0           not eax
|    ||||   0x08049672      83e801         sub eax, 1
|    ||||   0x08049675      898424d80000.  mov dword [esp + 0xd8], eax
|    ||||   0x0804967c      c784242c0100.  mov dword [esp + 0x12c], 0xfe ; [0xfe:4]=0x9cd80804 
|    ||||   0x08049687      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|    ||||   0x0804968c      898424000100.  mov dword [esp + 0x100], eax
|    ||||   0x08049693      a190b00408     mov eax, dword [obj.sndaccount] ; [0x804b090:4]=0x206f7261  LEA obj.sndaccount ; "aro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b090
|    ||||   0x08049698      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x080496a0      89c2           mov edx, eax
|    ||||   0x080496a2      b800000000     mov eax, 0
|    ||||   0x080496a7      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x080496ab      89d7           mov edi, edx
|    ||||   0x080496ad      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x080496af      89c8           mov eax, ecx
|    ||||   0x080496b1      f7d0           not eax
|    ||||   0x080496b3      83e801         sub eax, 1
|    ||||   0x080496b6      898424180100.  mov dword [esp + 0x118], eax
|    ||||   0x080496bd      c784246c0100.  mov dword [esp + 0x16c], 3
|    ||||   0x080496c8      8d8424d00200.  lea eax, [esp + 0x2d0]      ; 0x2d0 
|    ||||   0x080496cf      898424400100.  mov dword [esp + 0x140], eax
|    ||||   0x080496d6      c78424ac0100.  mov dword [esp + 0x1ac], 0xfe ; [0xfe:4]=0x9cd80804 
|    ||||   0x080496e1      8d8424b80500.  lea eax, [esp + 0x5b8]      ; 0x5b8 
|    ||||   0x080496e8      898424800100.  mov dword [esp + 0x180], eax
|    ||||   0x080496ef      8d8424b80500.  lea eax, [esp + 0x5b8]      ; 0x5b8 
|    ||||   0x080496f6      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x080496fe      89c2           mov edx, eax
|    ||||   0x08049700      b800000000     mov eax, 0
|    ||||   0x08049705      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x08049709      89d7           mov edi, edx
|    ||||   0x0804970b      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x0804970d      89c8           mov eax, ecx
|    ||||   0x0804970f      f7d0           not eax
|    ||||   0x08049711      83e801         sub eax, 1
|    ||||   0x08049714      898424980100.  mov dword [esp + 0x198], eax
|    ||||   0x0804971b      c78424ec0100.  mov dword [esp + 0x1ec], 0xfe ; [0xfe:4]=0x9cd80804 
|    ||||   0x08049726      8d8424b80500.  lea eax, [esp + 0x5b8]      ; 0x5b8 
|    ||||   0x0804972d      898424c00100.  mov dword [esp + 0x1c0], eax
|    ||||   0x08049734      8d8424b80500.  lea eax, [esp + 0x5b8]      ; 0x5b8 
|    ||||   0x0804973b      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x08049743      89c2           mov edx, eax
|    ||||   0x08049745      b800000000     mov eax, 0
|    ||||   0x0804974a      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x0804974e      89d7           mov edi, edx
|    ||||   0x08049750      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x08049752      89c8           mov eax, ecx
|    ||||   0x08049754      f7d0           not eax
|    ||||   0x08049756      83e801         sub eax, 1
|    ||||   0x08049759      898424d80100.  mov dword [esp + 0x1d8], eax
|    ||||   0x08049760      c784242c0200.  mov dword [esp + 0x22c], 0xfe ; [0xfe:4]=0x9cd80804 
|    ||||   0x0804976b      8b44242c       mov eax, dword [esp + 0x2c] ; [0x2c:4]=0x280009  ; ','
|    ||||   0x0804976f      8b4010         mov eax, dword [eax + 0x10] ; [0x10:4]=0x30002 
|    ||||   0x08049772      898424000200.  mov dword [esp + 0x200], eax
|    ||||   0x08049779      8b44242c       mov eax, dword [esp + 0x2c] ; [0x2c:4]=0x280009  ; ','
|    ||||   0x0804977d      83c010         add eax, 0x10
|    ||||   0x08049780      8b00           mov eax, dword [eax]
|    ||||   0x08049782      c7442428ffff.  mov dword [esp + 0x28], loc.imp._Jv_RegisterClasses ; [0xffffffff:4]=-1 LEA loc.imp._Jv_RegisterClasses ; loc.imp._Jv_RegisterClasses
|    ||||   0x0804978a      89c2           mov edx, eax
|    ||||   0x0804978c      b800000000     mov eax, 0
|    ||||   0x08049791      8b4c2428       mov ecx, dword [esp + 0x28] ; [0x28:4]=0x200034  ; '('
|    ||||   0x08049795      89d7           mov edi, edx
|    ||||   0x08049797      f2ae           repne scasb al, byte es:[edi]
|    ||||   0x08049799      89c8           mov eax, ecx
|    ||||   0x0804979b      f7d0           not eax
|    ||||   0x0804979d      83e801         sub eax, 1
|    ||||   0x080497a0      898424180200.  mov dword [esp + 0x218], eax
|    ||||   0x080497a7      8d442438       lea eax, [esp + 0x38]       ; 0x38  ; '8'
|    ||||   0x080497ab      89442404       mov dword [esp + 4], eax
|    ||||   0x080497af      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x080497b6      890424         mov dword [esp], eax
|    ||||   0x080497b9      e8f2f1ffff     call sym.imp.mysql_stmt_bind_param
|    ||||   0x080497be      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|    ||||   0x080497c5      890424         mov dword [esp], eax
|    ||||   0x080497c8      e8d3f1ffff     call sym.imp.mysql_stmt_execute
|    ||||   0x080497cd      85c0           test eax, eax
|   ,=====< 0x080497cf      741e           je 0x80497ef               
|   |||||   0x080497d1      c70424689c04.  mov dword [esp], str.Error_happend_while_inserting_transaction_details_to_the_transaction_table ; [0x8049c68:4]=0x6f727245  LEA str.Error_happend_while_inserting_transaction_details_to_the_transaction_table ; "Error happend while inserting transaction details to the transaction table" @ 0x8049c68
|   |||||   0x080497d8      e8a3f0ffff     call sym.imp.puts
|   |||||   0x080497dd      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|   |||||   0x080497e4      890424         mov dword [esp], eax
|   |||||   0x080497e7      e8b7f2ffff     call sym.finish_with_error
|  ,======< 0x080497ec      eb01           jmp 0x80497ef              
|  ||||||   ; JMP XREF from 0x08048f88 (sym.main)
|  ||||||   ; JMP XREF from 0x08048f67 (sym.main)
|  ||||``-> 0x080497ee      90             nop
|  ||||     ; JMP XREF from 0x080497ec (sym.main)
|  ||||     ; JMP XREF from 0x080497cf (sym.main)
|  ``-----> 0x080497ef      a18cb00408     mov eax, dword [obj.fid]    ; [0x804b08c:4]=0x6e694c2f  LEA obj.fid ; "/Linaro 4.6.3-1ubuntu5) 4.6.3" @ 0x804b08c
|    ||     0x080497f4      890424         mov dword [esp], eax
|    ||     0x080497f7      e8e4f0ffff     call sym.imp.feof
|    ||     0x080497fc      85c0           test eax, eax
|    |`===< 0x080497fe      0f8489f7ffff   je 0x8048f8d               
|    |  ,=< 0x08049804      eb01           jmp 0x8049807              
|    |  |   ; JMP XREF from 0x08048fbc (sym.main)
|    `----> 0x08049806      90             nop
|       |   ; JMP XREF from 0x08049804 (sym.main)
|       `-> 0x08049807      c70424b39c04.  mov dword [esp], str.All_transactions_completed ; [0x8049cb3:4]=0x206c6c41  LEA str.All_transactions_completed ; "All transactions completed" @ 0x8049cb3
|           0x0804980e      e86df0ffff     call sym.imp.puts
|           0x08049813      e85cf2ffff     call sym.close_free
|           0x08049818      8b8424e80200.  mov eax, dword [esp + 0x2e8] ; [0x2e8:4]=153
|           0x0804981f      890424         mov dword [esp], eax
|           0x08049822      e889f0ffff     call sym.imp.mysql_stmt_close
|           0x08049827      8b8424e40200.  mov eax, dword [esp + 0x2e4] ; [0x2e4:4]=18
|           0x0804982e      890424         mov dword [esp], eax
|           0x08049831      e83af1ffff     call sym.imp.mysql_close
|           0x08049836      c70424000000.  mov dword [esp], 0
|           0x0804983d      e87ef0ffff     call sym.imp.exit
|           0x08049842      90             nop
|           0x08049843      90             nop
|           0x08049844      90             nop
|           0x08049845      90             nop
|           0x08049846      90             nop
|           0x08049847      90             nop
|           0x08049848      90             nop
|           0x08049849      90             nop
|           0x0804984a      90             nop
|           0x0804984b      90             nop
|           0x0804984c      90             nop
|           0x0804984d      90             nop
|           0x0804984e      90             nop
\           0x0804984f      90             nop
