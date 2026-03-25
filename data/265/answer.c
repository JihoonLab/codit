#include <stdio.h>
#include <math.h>
int main(void) {
    long long n;
    scanf("%lld", &n);
    long long r = (long long)sqrt((double)n);
    while (r * r > n) r--;
    while ((r + 1) * (r + 1) <= n) r++;
    printf("%lld\n", r);
    return 0;
}
