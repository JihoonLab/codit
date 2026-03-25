#include <stdio.h>
int main(void) {
    long long a, b, c, mid;
    scanf("%lld %lld %lld", &a, &b, &c);
    if ((a >= b && a <= c) || (a <= b && a >= c)) mid = a;
    else if ((b >= a && b <= c) || (b <= a && b >= c)) mid = b;
    else mid = c;
    printf("%lld\n", mid);
    return 0;
}
