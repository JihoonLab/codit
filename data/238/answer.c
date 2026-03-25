#include <stdio.h>
int main(void) {
    int n, i, a, b;
    scanf("%d", &n);
    for (i = 0; i < n; i += 2) {
        scanf("%d", &a);
        if (i + 1 < n) {
            scanf("%d", &b);
            printf("%d ", a > b ? a : b);
        } else {
            printf("%d ", a);
        }
    }
    printf("\n");
    return 0;
}
