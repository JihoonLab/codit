#include <stdio.h>
int main(void) {
    int m, n, i, j;
    scanf("%d %d", &m, &n);
    for (i = 0; i < m; i++) {
        for (j = 0; j < n; j++) {
            printf("%d ", (n - 1 - j) * m + i + 1);
        }
        printf("\n");
    }
    return 0;
}
