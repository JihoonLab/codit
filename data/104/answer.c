#include <stdio.h>
int main() {
    int n, i, a;
    int count[24] = {0};
    scanf("%d", &n);
    for (i = 0; i < n; i++) {
        scanf("%d", &a);
        if (a >= 1 && a <= 23) count[a]++;
    }
    for (i = 1; i <= 23; i++) {
        if (i > 1) printf(" ");
        printf("%d", count[i]);
    }
    printf("\n");
    return 0;
}
