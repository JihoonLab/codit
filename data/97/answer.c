#include <stdio.h>
int main() {
    long long a, d, n;
    scanf("%lld %lld %lld", &a, &d, &n);
    printf("%lld\n", a + (n - 1) * d);
    return 0;
}
