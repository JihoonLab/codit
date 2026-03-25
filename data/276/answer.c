#include <stdio.h>
int main(void) {
    long long a, b, d;
    scanf("%lld %lld", &a, &b);
    d = a - b;
    if (d < 0) d = -d;
    printf("%lld\n", d);
    return 0;
}
