#include <stdio.h>
int main(void) {
    int n, i, a[1000], idx = 0;
    scanf("%d", &n);
    for (i = 0; i < n; i++) scanf("%d", &a[i]);
    for (i = 1; i < n; i++) if (a[i] > a[idx]) idx = i;
    printf("%d\n", idx + 1);
    return 0;
}
