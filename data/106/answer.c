#include <stdio.h>
int main() {
    int n, i, a, min_val;
    scanf("%d", &n);
    scanf("%d", &min_val);
    for (i = 1; i < n; i++) {
        scanf("%d", &a);
        if (a < min_val) min_val = a;
    }
    printf("%d\n", min_val);
    return 0;
}
