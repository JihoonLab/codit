#include <stdio.h>
void triangle(int i, int n) {
    if (i > n) return;
    for (int j = 1; j <= i; j++) {
        if (j > 1) printf(" ");
        printf("%d", j);
    }
    printf("\n");
    triangle(i + 1, n);
}
int main(void) {
    int n;
    scanf("%d", &n);
    triangle(1, n);
    return 0;
}
