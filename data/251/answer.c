#include <stdio.h>
int main(void) {
    int n, i, val, mn;
    scanf("%d", &n);
    for (i = 0; i < n; i++) {
        scanf("%d", &val);
        if (i == 0) mn = val;
        else if (val < mn) mn = val;
    }
    printf("%d\n", mn);
    return 0;
}
