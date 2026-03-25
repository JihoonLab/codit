#include <stdio.h>
int main(void) {
    long long a, b, t, ga, gb;
    scanf("%lld %lld", &a, &b);
    ga = a; gb = b;
    if (ga < 0) ga = -ga;
    if (gb < 0) gb = -gb;
    long long ta = ga, tb = gb;
    while (tb) { t = tb; tb = ta % tb; ta = t; }
    printf("%lld\n", ga / ta * gb);
    return 0;
}
