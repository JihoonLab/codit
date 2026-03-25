#include <stdio.h>
int main(void) {
    int m, n, i, j;
    scanf("%d %d", &m, &n);
    for (i = 0; i < m; i++) {
        for (j = 0; j < n; j++) {
            printf("%d ", (m - 1 - i) * n + j + 1);
        }
        printf("\n");
    }
    return 0;
}
