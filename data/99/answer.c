#include <stdio.h>
int main() {
    long long a, r, c, n;
    scanf("%lld %lld %lld %lld", &a, &r, &c, &n);
    long long sum = 0;
    long long term = a;
    long long i;
    for (i = 0; i < n; i++) {
        sum += term;
        term *= r;
    }
    printf("%lld\n", sum);
    return 0;
}
