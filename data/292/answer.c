#include <stdio.h>
long long fib(int n) {
    if (n <= 2) return 1;
    return fib(n - 1) + fib(n - 2);
}
int main(void) {
    int n;
    scanf("%d", &n);
    printf("%lld\n", fib(n));
    return 0;
}
