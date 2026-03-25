#include <stdio.h>
long long memo[100] = {0};
long long fib(int n) {
    if (n <= 2) return 1;
    if (memo[n]) return memo[n];
    memo[n] = fib(n - 1) + fib(n - 2);
    return memo[n];
}
int main(void) {
    int n;
    scanf("%d", &n);
    printf("%lld\n", fib(n));
    return 0;
}
