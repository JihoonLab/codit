#include <stdio.h>
int main() {
    int n, k, s = 0;
    scanf("%d", &n);
    for (k = 1; ; k++) {
        s += k;
        if (s >= n) { printf("%d\n", k); break; }
    }
    return 0;
}
