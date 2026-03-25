#include <stdio.h>
int main() {
    int n, i, first = 1;
    scanf("%d", &n);
    for (i = 1; i <= n; i++) {
        if (i % 3 != 0) {
            if (!first) printf(" ");
            printf("%d", i);
            first = 0;
        }
    }
    printf("\n");
    return 0;
}
