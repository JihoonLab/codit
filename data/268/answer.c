#include <stdio.h>
#include <math.h>
int main(void) {
    double x;
    scanf("%lf", &x);
    double frac = x - floor(x);
    printf("%.14f\n", frac);
    return 0;
}
