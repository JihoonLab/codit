#include <stdio.h>
int digitSum(int n) {
    if (n < 0) n = -n;
    if (n < 10) return n;
    return n % 10 + digitSum(n / 10);
}
int main(void) {
    int n;
    scanf("%d", &n);
    printf("%d\n", digitSum(n));
    return 0;
}
