#include <stdio.h>
int main(void) {
    long long n, r = 0, neg = 0;
    scanf("%lld", &n);
    if (n < 0) { neg = 1; n = -n; }
    while (n > 0) { r = r * 10 + n % 10; n /= 10; }
    if (neg) r = -r;
    printf("%lld\n", r);
    return 0;
}
