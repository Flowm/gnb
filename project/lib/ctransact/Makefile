NAME := $(notdir $(CURDIR))

C_FILES := $(wildcard src/*.c)
OBJ_FILES := $(addprefix obj/,$(notdir $(C_FILES:.c=.o)))

CC = gcc
CC_FLAGS := -Wall -Wextra
LD_FLAGS :=

all: $(NAME)

$(NAME): $(OBJ_FILES)
	$(CC) $(LD_FLAGS) -o $@ $^

$(OBJ_FILES): $(C_FILES)
	$(CC) $(CC_FLAGS) -c $< -o $@

clean:
	@rm -f $(OBJ_FILES)
	@rm -f $(NAME)
