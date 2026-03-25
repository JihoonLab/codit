#include <stdio.h>
int main() {
    char c;
    while (scanf(" %c", &c) == 1) {
        printf("%c\n", c);
        if (c == 'q') break;
    }
    return 0;
}
