#include <stdio.h>
long long comb(int n, int r) {
    if (r > n) return 0;
    if (r == 0 || r == n) return 1;
    return comb(n - 1, r - 1) + comb(n - 1, r);
}
void pascal(int row, int n) {
    if (row > n) return;
    for (int j = 0; j < row; j++) {
        if (j > 0) printf(" ");
        printf("%lld", comb(row - 1, j));
    }
    printf("\n");
    pascal(row + 1, n);
}
int main(void) {
    int n;
    scanf("%d", &n);
    pascal(1, n);
    return 0;
}
