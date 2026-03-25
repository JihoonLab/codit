#include <stdio.h>
int main(void) {
    int m, n, i, j;
    long long a[100][100];
    scanf("%d %d", &m, &n);
    for (i = 0; i < m; i++)
        for (j = 0; j < n; j++)
            scanf("%lld", &a[i][j]);
    for (i = 0; i < m; i++)
        for (j = 1; j < n; j++)
            a[i][j] += a[i][j - 1];
    for (i = 0; i < m; i++) {
        for (j = 0; j < n; j++) printf("%lld ", a[i][j]);
        printf("\n");
    }
    return 0;
}
