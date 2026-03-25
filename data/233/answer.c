#include <stdio.h>
int main(void) {
    int n, i;
    long long a[1000];
    scanf("%d", &n);
    for (i = 0; i < n; i++) scanf("%lld", &a[i]);
    for (i = 1; i < n; i++) a[i] += a[i - 1];
    for (i = 0; i < n; i++) printf("%lld ", a[i]);
    printf("\n");
    return 0;
}
