#include <stdio.h>
long long climb(int n) {
    if (n <= 1) return 1;
    if (n == 2) return 2;
    return climb(n - 1) + climb(n - 2);
}
int main(void) {
    int n;
    scanf("%d", &n);
    printf("%lld\n", climb(n));
    return 0;
}
