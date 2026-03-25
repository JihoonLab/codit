#include <stdio.h>
int main(void) {
    int n, m, i, l, r;
    long long d[1001] = {0}, a[1001] = {0}, v;
    scanf("%d %d", &n, &m);
    for (i = 0; i < m; i++) {
        scanf("%d %d %lld", &l, &r, &v);
        d[l] += v;
        if (r + 1 <= n) d[r + 1] -= v;
    }
    for (i = 1; i <= n; i++) printf("%lld ", d[i]);
    printf("\n");
    a[1] = d[1];
    for (i = 2; i <= n; i++) a[i] = a[i - 1] + d[i];
    for (i = 1; i <= n; i++) printf("%lld ", a[i]);
    printf("\n");
    return 0;
}
