NAME := $(notdir $(CURDIR))

C_FILES := $(wildcard src/*.c)
OBJ_FILES := $(addprefix obj/,$(notdir $(C_FILES:.c=.o)))

CC = gcc
CC_FLAGS := -Wall -Wextra $(shell mysql_config --cflags) -Wno-unused-variable -Wno-unused-but-set-variable
LD_FLAGS :=
LIBS := $(shell mysql_config --libs)

all: $(NAME)

$(OBJ_FILES): $(C_FILES)
	$(CC) $(CC_FLAGS) -c $< -o $@

$(NAME): $(OBJ_FILES)
	$(CC) $(LD_FLAGS) -o $@ $^ $(LIBS)

clean:
	@rm -f $(OBJ_FILES)
	@rm -f $(NAME)
