#include <stdio.h>
int main(void) {
    int n, i, l, r, idx;
    long long a[10000];
    scanf("%d", &n);
    for (i = 0; i < n; i++) scanf("%lld", &a[i]);
    scanf("%d %d", &l, &r);
    idx = l - 1;
    for (i = l; i < r; i++)
        if (a[i] > a[idx]) idx = i;
    printf("%d\n", idx + 1);
    return 0;
}
