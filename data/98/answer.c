#include <stdio.h>
int main() {
    long long a, r, n;
    scanf("%lld %lld %lld", &a, &r, &n);
    long long result = a;
    long long i;
    for (i = 1; i < n; i++) {
        result *= r;
    }
    printf("%lld\n", result);
    return 0;
}
