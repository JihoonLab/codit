#include <stdio.h>
long long sumN(int n) {
    if (n <= 0) return 0;
    return n + sumN(n - 1);
}
int main(void) {
    int n;
    scanf("%d", &n);
    printf("%lld\n", sumN(n));
    return 0;
}
