#include <stdio.h>
int main() {
    int a;
    while (scanf("%d", &a) == 1) {
        if (a == 0) break;
        printf("%d\n", a);
    }
    return 0;
}
