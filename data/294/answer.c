#include <stdio.h>
long long comb(int n, int r) {
    if (r > n) return 0;
    if (r == 0 || r == n) return 1;
    return comb(n - 1, r - 1) + comb(n - 1, r);
}
int main(void) {
    int n, r;
    scanf("%d %d", &n, &r);
    printf("%lld\n", comb(n, r));
    return 0;
}
