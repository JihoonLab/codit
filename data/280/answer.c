#include <stdio.h>
int main(void) {
    long long a, b, t;
    scanf("%lld %lld", &a, &b);
    if (a < 0) a = -a;
    if (b < 0) b = -b;
    while (b) { t = b; b = a % b; a = t; }
    printf("%lld\n", a);
    return 0;
}
