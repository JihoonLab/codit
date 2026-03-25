#include <stdio.h>
int main(void) {
    int m, n, q, i, j, r1, c1, r2, c2;
    long long d[102][102] = {0}, a[102][102] = {0}, v;
    scanf("%d %d %d", &m, &n, &q);
    for (i = 0; i < q; i++) {
        scanf("%d %d %d %d %lld", &r1, &c1, &r2, &c2, &v);
        d[r1][c1] += v;
        if (c2 + 1 <= n) d[r1][c2 + 1] -= v;
        if (r2 + 1 <= m) d[r2 + 1][c1] -= v;
        if (r2 + 1 <= m && c2 + 1 <= n) d[r2 + 1][c2 + 1] += v;
    }
    for (i = 1; i <= m; i++) {
        for (j = 1; j <= n; j++) printf("%lld ", d[i][j]);
        printf("\n");
    }
    printf("\n");
    for (i = 1; i <= m; i++)
        for (j = 1; j <= n; j++)
            a[i][j] = d[i][j] + a[i-1][j] + a[i][j-1] - a[i-1][j-1];
    for (i = 1; i <= m; i++) {
        for (j = 1; j <= n; j++) printf("%lld ", a[i][j]);
        printf("\n");
    }
    return 0;
}
