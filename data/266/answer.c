#include <stdio.h>
int main(void) {
    int n, i, a[10000], target, found = -1;
    scanf("%d", &n);
    for (i = 0; i < n; i++) scanf("%d", &a[i]);
    scanf("%d", &target);
    for (i = 0; i < n; i++) {
        if (a[i] == target) { found = i + 1; break; }
    }
    printf("%d\n", found);
    return 0;
}
