#include <stdio.h>
int main(void) {
    double x;
    scanf("%lf", &x);
    int r = (int)x;
    if (x < 0 && x != r) r--;
    printf("%d\n", r);
    return 0;
}
