#include <stdio.h>
int main(void) {
    int n, i;
    long long f = 1;
    scanf("%d", &n);
    for (i = 2; i <= n; i++) f *= i;
    printf("%lld\n", f);
    return 0;
}
