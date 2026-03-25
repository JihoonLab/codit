#include <stdio.h>
int main(void) {
    long long a, b, r = 1;
    int i;
    scanf("%lld %lld", &a, &b);
    for (i = 0; i < b; i++) r *= a;
    printf("%lld\n", r);
    return 0;
}
