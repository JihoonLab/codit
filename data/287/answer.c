#include <stdio.h>
int main(void) {
    int n, i, a[10000], target;
    scanf("%d", &n);
    for (i = 0; i < n; i++) scanf("%d", &a[i]);
    scanf("%d", &target);
    int lo = 0, hi = n;
    while (lo < hi) {
        int mid = (lo + hi) / 2;
        if (a[mid] <= target) lo = mid + 1;
        else hi = mid;
    }
    printf("%d\n", lo + 1);
    return 0;
}
