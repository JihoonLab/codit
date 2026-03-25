#include <stdio.h>
int main(void) {
    long long n;
    scanf("%lld", &n);
    if (n < 0) n = -n;
    printf("%lld\n", n);
    return 0;
}
