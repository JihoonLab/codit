#include <stdio.h>
int main(void) {
    int n, i, j;
    scanf("%d", &n);
    for (i = 0; i < n; i++) {
        for (j = 0; j < n; j++) {
            printf("%d ", j * n + (n - 1 - i) + 1);
        }
        printf("\n");
    }
    return 0;
}
