#include <stdio.h>
int main(void) {
    int n, i, is_prime = 1;
    scanf("%d", &n);
    if (n < 2) is_prime = 0;
    else {
        for (i = 2; i * i <= n; i++)
            if (n % i == 0) { is_prime = 0; break; }
    }
    if (is_prime) printf("prime\n");
    else printf("composite\n");
    return 0;
}
