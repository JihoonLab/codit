#include <stdio.h>
int main(void) {
    int n, i, l, r;
    long long a[10000], sum = 0;
    scanf("%d", &n);
    for (i = 0; i < n; i++) scanf("%lld", &a[i]);
    scanf("%d %d", &l, &r);
    for (i = l - 1; i < r; i++) sum += a[i];
    printf("%lld\n", sum);
    return 0;
}
